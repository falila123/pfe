<?php

namespace App\Models;

use App\Notifications\DemandeTraitee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Demande extends Model
{
    protected $fillable = [
        'user_id',
        'livre_id',
        'exemplaire_id',
        'statut',
        'motif_refus',
        'date_limite_retrait',
    ];

    protected $casts = [
        'date_limite_retrait' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function exemplaire()
    {
        return $this->belongsTo(Exemplaire::class);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPIRATION AUTO : réservations 24h non récupérées
    |--------------------------------------------------------------------------
    | Passe les réservations dépassées en "Expirée" et relibère l'exemplaire.
    | Retourne le nombre de réservations expirées.
    */
    public static function expirerReservationsDepassees(): int
    {
        $reservations = static::with('exemplaire', 'user')
            ->where('statut', 'Acceptée')
            ->whereNotNull('date_limite_retrait')
            ->where('date_limite_retrait', '<', now())
            ->get();

        $count = 0;

        foreach ($reservations as $demande) {

            DB::transaction(function () use ($demande) {

                // Relibérer l'exemplaire s'il est toujours réservé
                if ($demande->exemplaire && $demande->exemplaire->statut === 'Réservé') {
                    $demande->exemplaire->update(['statut' => 'Disponible']);
                }

                $demande->update(['statut' => 'Expirée']);
            });

            // 🔔 Notifier le membre
            $demande->refresh();
            $demande->user?->notify(new DemandeTraitee($demande));

            $count++;
        }

        return $count;
    }
}
