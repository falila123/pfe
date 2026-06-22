<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountInvitation extends Notification
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
        // Lien vers la page de définition du mot de passe (réutilise le flux reset de Breeze)
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return (new MailMessage)
            ->subject('Activez votre compte - Bibliothèque universitaire')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Un compte vient d'être créé pour vous sur la plateforme de la Bibliothèque universitaire.")
            ->line('Pour activer votre compte, définissez votre mot de passe personnel en cliquant sur le bouton ci-dessous.')
            ->action('Définir mon mot de passe', $url)
            ->line("Ce lien est valable un temps limité. Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.")
            ->withSymfonyMessage(function (\Symfony\Component\Mime\Email $message) {
                $message->embedFromPath(public_path('images/logo-email.png'), 'logo');
            });
    }
}
