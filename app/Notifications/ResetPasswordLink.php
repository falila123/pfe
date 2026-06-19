<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordLink extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — Bibliothèque universitaire')
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Vous avez demandé la réinitialisation de votre mot de passe.')
            ->line('Cliquez sur le bouton ci-dessous pour en définir un nouveau.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line("Ce lien est valable un temps limité. Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email — votre mot de passe restera inchangé.");
    }
}
