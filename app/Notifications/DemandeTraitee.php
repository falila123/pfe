<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Notifications\Notification;

class DemandeTraitee extends Notification
{
    public function __construct(public Demande $demande)
    {
    }

    /**
     * Canaux d'envoi.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Données stockées en base (colonne data).
     */
    public function toArray($notifiable): array
    {
        $titre = $this->demande->livre->titre ?? 'le livre';

        return [
            'demande_id' => $this->demande->id,
            'livre'      => $titre,
            'statut'     => $this->demande->statut,
            'motif'      => $this->demande->motif_refus,
            'message'    => $this->demande->statut === 'Acceptée'
                ? "Votre demande pour « {$titre} » a été acceptée ✅"
                : "Votre demande pour « {$titre} » a été refusée ❌",
        ];
    }
}
