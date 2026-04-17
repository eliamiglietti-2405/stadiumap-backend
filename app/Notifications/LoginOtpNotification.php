<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Codice di verifica StadiuMap')
            ->greeting('Accesso a StadiuMap')
            ->line('Il tuo codice di verifica è: ' . $this->code)
            ->line('Il codice scade tra 10 minuti.')
            ->line('Se non sei stato tu, ignora questa email.');
    }
}