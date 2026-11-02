<?php

namespace App\Http\Controllers;

use App\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index()
    {
        Gate::authorize('moderate-comments');

        $comments = Comment::with(['user', 'article'])
            ->where('is_approved', false)
            ->oldest('id')
            ->paginate(10);

        return view('comments.moderation', ['comments' => $comments]);
    }

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
            'Комментарий отправлен и ожидает модерации.'
        );
    }

    public function approve(Comment $comment)
    {
        Gate::authorize('moderate-comments');

        $approved = Comment::whereKey($comment->id)
            ->where('is_approved', false)
            ->update(['is_approved' => true]);

        abort_unless($approved, 404);

        return redirect()->route('comments.moderation')
            ->with('success', 'Комментарий принят и опубликован.');
    }

    public function reject(Comment $comment)
    {
        Gate::authorize('moderate-comments');

        $deleted = Comment::whereKey($comment->id)
            ->where('is_approved', false)
            ->delete();

        abort_unless($deleted, 404);

        return redirect()->route('comments.moderation')
            ->with('success', 'Комментарий отклонён и удалён.');
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
