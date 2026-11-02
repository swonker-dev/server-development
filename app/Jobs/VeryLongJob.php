<?php

namespace App\Jobs;

use App\Article;
use App\Mail\ArticleCreated;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class VeryLongJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public int $timeout = 30;

    public bool $deleteWhenMissingModels = true;

    public function __construct(public Article $article, public User $moderator)
    {
    }

    public function handle(): void
    {
        if (! $this->moderator->isModerator()) {
            return;
        }

        if (config('mail.default') === 'smtp' && (
            blank(config('mail.mailers.smtp.username')) ||
            blank(config('mail.mailers.smtp.password'))
        )) {
            throw new RuntimeException('Не заполнены настройки SMTP для отправки уведомления.');
        }

        try {
            Mail::to($this->moderator->email)->send(new ArticleCreated($this->article));
        } catch (Exception $exception) {
            // Preserve retries without exposing SMTP credentials in worker logs.
            Log::warning('Queued article notification could not be sent.', [
                'article_id' => $this->article->id,
                'moderator_id' => $this->moderator->id,
                'exception_type' => get_class($exception),
            ]);

            throw new RuntimeException('Не удалось отправить уведомление модератору. Проверьте настройки почты.');
        }
    }
}
