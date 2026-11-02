<?php

namespace App\Console\Commands;

use App\Mail\DailyStatistics;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправить модераторам статистику сайта за текущий день';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $moderators = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })->get();

        if ($moderators->isEmpty()) {
            $this->error('Нет модераторов для отправки статистики.');

            return self::FAILURE;
        }

        if (config('mail.default') === 'smtp' && (
            blank(config('mail.mailers.smtp.username')) ||
            blank(config('mail.mailers.smtp.password'))
        )) {
            $this->error('Не заполнены настройки SMTP для отправки статистики.');

            return self::FAILURE;
        }

        $day = now(config('statistics.timezone'))->startOfDay();
        $start = $day->copy()->setTimezone(config('app.timezone'));
        $end = $day->copy()->addDay()->setTimezone(config('app.timezone'));

        $views = ArticleView::where('created_at', '>=', $start)
            ->where('created_at', '<', $end)->count();
        $comments = Comment::where('created_at', '>=', $start)
            ->where('created_at', '<', $end)->count();
        $date = $day->format('d.m.Y');
        $sent = 0;

        foreach ($moderators as $moderator) {
            try {
                Mail::to($moderator->email)->send(new DailyStatistics($date, $views, $comments));
                $sent++;
            } catch (Exception $exception) {
                Log::warning('Daily site statistics could not be sent.', [
                    'moderator_id' => $moderator->id,
                    'exception_type' => get_class($exception),
                ]);
            }
        }

        $this->line("Статистика за {$date}: просмотры — {$views}, новые комментарии — {$comments}.");

        if ($sent !== $moderators->count()) {
            $this->error("Отправлено писем: {$sent}. Не все отчёты отправлены. Проверьте настройки почты.");

            return self::FAILURE;
        }

        $this->info("Отчёт отправлен модераторам: {$sent}.");

        return self::SUCCESS;
    }
}
