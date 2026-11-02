<?php

namespace App\Http\Controllers;

use App\Article;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    public function open(Request $request, string $notification)
    {
        $notification = $request->user()->notifications()
            ->where('type', NewArticleNotification::class)
            ->findOrFail($notification);

        $article = Article::find($notification->data['article_id']);

        if (! $article) {
            $notification->markAsRead();

            return redirect()->route('articles.index')
                ->with('warning', 'Статья из уведомления уже удалена.');
        }

        Gate::authorize('view', $article);
        $notification->markAsRead();

        return redirect()->route('articles.show', $article);
    }
}
