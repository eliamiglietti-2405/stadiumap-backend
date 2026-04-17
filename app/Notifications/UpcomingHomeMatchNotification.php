<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingHomeMatchNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $teamName,
        private readonly string $stadiumName,
        private readonly string $opponentName,
        private readonly string $matchDate
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Partita in casa della tua squadra preferita')
            ->greeting('Ciao!')
            ->line("La squadra {$this->teamName} sta per giocare in casa.")
            ->line("Stadio: {$this->stadiumName}")
            ->line("Avversario: {$this->opponentName}")
            ->line("Data partita: {$this->matchDate}");
    }
}