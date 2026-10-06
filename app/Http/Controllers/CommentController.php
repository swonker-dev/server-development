<?php

namespace App\Http\Controllers;

use App\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        Gate::authorize('create-comment');

        $validated = $request->validate([
            'body' => [
                'required',
                'string',
                'min:2',
                'max:1000',
            ],
        ], [
            'body.required' => 'Введите текст комментария.',
            'body.min' => 'Комментарий должен содержать минимум 2 символа.',
            'body.max' => 'Комментарий не должен превышать 1000 символов.',
        ]);

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with(
            'success',
            'Комментарий добавлен.'
        );
    }


    public function destroy(Comment $comment)
    {
        Gate::authorize('delete-comment');

        $comment->delete();

        return back()->with(
            'success',
            'Комментарий удалён.'
        );
    }
}