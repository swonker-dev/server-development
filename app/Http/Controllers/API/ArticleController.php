<?php

namespace App\Http\Controllers\API;

use App\Article;
use App\Events\NewArticleEvent;
use App\Http\Controllers\Controller;
use App\Jobs\VeryLongJob;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    private const PER_PAGE = 5;

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Article::class);

        $page = Paginator::resolveCurrentPage();
        $key = 'articles.index.'.$page;
        $articles = Cache::remember($key, 600, function () use ($page) {
            return Article::latest()->paginate(self::PER_PAGE, ['*'], 'page', $page);
        });

        // Pagination links must use the current request's address, not the cached one.
        $articles->withPath(Paginator::resolveCurrentPath());

        // Only existing pages are retained, so store can forget every cached page.
        if ($page > $articles->lastPage()) {
            Cache::forget($key);
        }

        return response()->json([
            'articles' => $articles,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Article::class);
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
        ], [
            'title.required' => 'Введите заголовок статьи.',
            'title.min' => 'Заголовок должен содержать не менее 3 символов.',
            'title.max' => 'Заголовок не должен быть длиннее 255 символов.',

            'description.required' => 'Введите описание статьи.',
            'description.min' => 'Описание должно содержать не менее 10 символов.',
        ]);

        $article = DB::transaction(function () use ($validated, $request) {
            $article = Article::create($validated);

            $readers = User::whereHas('role', function ($query) {
                $query->where('name', 'reader');
            })->whereKeyNot($request->user()->id)->get();

            Notification::send($readers, new NewArticleNotification($article));

            return $article;
        });

        $lastPage = max(1, (int) ceil(Article::count() / self::PER_PAGE));

        for ($page = 1; $page <= $lastPage; $page++) {
            Cache::forget('articles.index.'.$page);
        }

        $queueFailed = false;

        try {
            NewArticleEvent::dispatch($article);
        } catch (Exception $exception) {
            $queueFailed = true;
            Log::warning('Article broadcast could not be queued.', [
                'article_id' => $article->id,
                'exception_type' => get_class($exception),
            ]);
        }

        $moderators = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })->get();

        $queueFailed = $queueFailed || $moderators->isEmpty();

        foreach ($moderators as $moderator) {
            try {
                VeryLongJob::dispatch($article, $moderator)->afterCommit();
            } catch (Exception $exception) {
                $queueFailed = true;
                Log::warning('Article notification could not be queued.', [
                    'article_id' => $article->id,
                    'moderator_id' => $moderator->id,
                    'exception_type' => get_class($exception),
                ]);
            }
        }

        $data = [
            'message' => 'Статья создана.',
            'article' => $article,
        ];

        if ($queueFailed) {
            $data['warning'] = 'Статья сохранена, но не все уведомления удалось запланировать.';
        }

        return response()->json($data, 201)
            ->header('Location', route('api.articles.show', $article));
    }

    public function show(Article $article): JsonResponse
    {
        Gate::authorize('view', $article);

        $article = Cache::rememberForever('articles.show.'.$article->id, function () use ($article) {
            return $article->load([
                'comments' => function ($query) {
                    $query->where('is_approved', true)->with('user:id,name')->oldest('id');
                },
            ]);
        });

        return response()->json([
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article): JsonResponse
    {
        Gate::authorize('update', $article);
        $rules = [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
        ];

        if ($request->isMethod('PATCH')) {
            $rules['title'][] = 'sometimes';
            $rules['description'][] = 'sometimes';
        }

        $validated = $request->validate($rules, [
            'title.required' => 'Введите заголовок статьи.',
            'title.min' => 'Заголовок должен содержать не менее 3 символов.',
            'title.max' => 'Заголовок не должен быть длиннее 255 символов.',

            'description.required' => 'Введите описание статьи.',
            'description.min' => 'Описание должно содержать не менее 10 символов.',
        ]);

        $article->update($validated);
        Cache::flush();

        return response()->json([
            'message' => 'Статья обновлена.',
            'article' => $article,
        ]);
    }

    public function destroy(Article $article): JsonResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();
        Cache::flush();

        return response()->json(['message' => 'Статья удалена.']);
    }
}
