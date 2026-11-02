<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyStatistics extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $date, public int $views, public int $comments)
    {
    }

    public function build(): static
    {
        return $this
            ->subject('Статистика за '.$this->date.' — '.config('app.name'))
            ->view('emails.statistics.daily');
    }
}
