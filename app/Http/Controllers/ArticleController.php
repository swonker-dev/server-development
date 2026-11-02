<?php

namespace App\Http\Controllers;

use App\Article;
use App\Mail\ArticleCreated;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $article = Article::create($validated);

        $moderators = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })->get();

        $notificationFailed = $moderators->isEmpty();

        if (config('mail.default') === 'smtp' && (
            blank(config('mail.mailers.smtp.username')) ||
            blank(config('mail.mailers.smtp.password'))
        )) {
            $notificationFailed = true;
            Log::warning('Article notification: SMTP credentials are missing.', [
                'article_id' => $article->id,
            ]);
        } else {
            foreach ($moderators as $moderator) {
                try {
                    Mail::to($moderator->email)->send(new ArticleCreated($article));
                } catch (Exception $exception) {
                    $notificationFailed = true;
                    // Do not log SMTP credentials or the contents of the email.
                    Log::warning('Article notification could not be sent.', [
                        'article_id' => $article->id,
                        'exception_type' => get_class($exception),
                    ]);
                }
            }
        }

        $response = redirect()
            ->route('articles.index')
            ->with('success', 'Статья создана.');

        if ($notificationFailed) {
            $response->with('warning', 'Статья сохранена, но не все уведомления модераторам удалось отправить.');
        }

        return $response;
    }

    public function show(Article $article)
    {
        Gate::authorize('view', $article);

        $article->load([
            'comments.user',
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
