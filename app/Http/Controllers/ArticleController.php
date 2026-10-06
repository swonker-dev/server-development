<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        Article::create($validated);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья создана.');
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
