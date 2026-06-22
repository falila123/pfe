<?php

namespace App\Notifications;

use App\Models\Emprunt;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmpruntRappel extends Notification
{
    use Queueable;

    public function __construct(public Emprunt $emprunt)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $titre = $this->emprunt->livre->titre ?? 'votre livre';
        $date  = $this->emprunt->date_retour_prevue?->format('d/m/Y');

        return (new MailMessage)
            ->subject('Rappel - livre à rendre')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Petit rappel : votre emprunt « {$titre} » est à rendre **demain ({$date})**.")
            ->line('Pensez à le rapporter à temps pour éviter tout retard.')
            ->action('Voir mes emprunts', route('etudiant.emprunts.index'))
            ->line('Merci, et bonne lecture !')
            ->withSymfonyMessage(function (\Symfony\Component\Mime\Email $message) {
                $message->embedFromPath(public_path('images/logo-email.png'), 'logo');
            });
    }
}
