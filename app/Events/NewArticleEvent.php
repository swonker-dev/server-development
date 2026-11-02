<?php

namespace App\Events;

use App\Article;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewArticleEvent implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public int $timeout = 30;

    public function __construct(public Article $article)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('articles'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'article' => [
                'id' => $this->article->id,
                'title' => $this->article->title,
            ],
        ];
    }
}
