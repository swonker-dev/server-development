<?php

namespace App\Http\Controllers;

use App\Article;
use App\Events\NewArticleEvent;
use App\Jobs\VeryLongJob;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    public function index()
{
    Gate::authorize('viewAny', Article::class);

    $articles = Article::latest()->paginate(5);

    return view('articles.index', [
        'articles' => $articles,
    ]);
}

    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('articles.create');
    }

    public function store(Request $request)
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

        $response = redirect()
            ->route('articles.index')
            ->with('success', 'Статья создана.');

        if ($queueFailed) {
            $response->with('warning', 'Статья сохранена, но не все уведомления удалось запланировать.');
        }

        return $response;
    }

    public function show(Article $article)
    {
        Gate::authorize('view', $article);

        $article->load([
            'comments' => function ($query) {
                $query->where('is_approved', true)->with('user')->oldest('id');
            },
        ]);

        return view('articles.show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);
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

        $article->update($validated);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья обновлена.');
    }

    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);
    
        $article->delete();
    
        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья удалена.');
    }
}
