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
        $titre  = $this->demande->livre->titre ?? 'le livre';
        $limite = $this->demande->date_limite_retrait?->format('d/m/Y à H:i');

        $message = match ($this->demande->statut) {
            'Acceptée'  => "Votre demande pour « {$titre} » est acceptée ✅ — récupérez le livre avant le {$limite}.",
            'Refusée'   => "Votre demande pour « {$titre} » a été refusée ❌",
            'Récupérée' => "Vous avez récupéré « {$titre} » 📚 Bonne lecture !",
            'Expirée'   => "Votre réservation de « {$titre} » a expiré ⏱️ (livre non récupéré à temps).",
            default     => "Mise à jour de votre demande pour « {$titre} ».",
        };

        return [
            'demande_id' => $this->demande->id,
            'livre'      => $titre,
            'statut'     => $this->demande->statut,
            'motif'      => $this->demande->motif_refus,
            'message'    => $message,
        ];
    }
}
