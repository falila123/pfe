<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Emprunt extends Model
{
    protected $fillable = [
        'livre_id',
        'user_id',
        'demande_id',
        'exemplaire_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
        'statut'
    ];

    // 🗓️ Les dates deviennent des objets Carbon
    protected $casts = [
        'date_emprunt'          => 'date',
        'date_retour_prevue'    => 'date',
        'date_retour_effective' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exemplaire()
    {
        return $this->belongsTo(Exemplaire::class);
    }

    // null = emprunt effectué sur place (sans demande en ligne)
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ÉTAT DYNAMIQUE (équivalent de getBorrowStatus / getBorrowIndication)
    |--------------------------------------------------------------------------
    */

    // 🔴 En retard = pas encore retourné ET date prévue dépassée
    public function getEnRetardAttribute(): bool
    {
        return $this->statut === 'En cours'
            && $this->date_retour_prevue->copy()->startOfDay()->lt(Carbon::today());
    }

    // 🏷️ Libellé du statut affiché
    public function getStatutAfficheAttribute(): string
    {
        if ($this->statut === 'Retourné') {
            return 'Retourné';
        }

        return $this->en_retard ? 'En retard' : 'En cours';
    }

    // 🎨 Classe Bootstrap du statut
    public function getStatutClasseAttribute(): string
    {
        if ($this->statut === 'Retourné') {
            return 'bg-success';
        }

        return $this->en_retard ? 'bg-danger' : 'bg-warning text-dark';
    }

    // 📝 Indication (jours restants / retard / avance)
    public function getIndicationAttribute(): string
    {
        // ✅ Déjà retourné → on compare prévu vs effectif
        if ($this->statut === 'Retourné' && $this->date_retour_effective) {

            $diff = $this->joursEntre(
                $this->date_retour_effective,
                $this->date_retour_prevue
            );

            if ($diff === 0) return 'Retourné à temps';
            if ($diff > 0)   return $diff . " jour(s) d'avance";
            return abs($diff) . ' jour(s) de retard';
        }

        // 🔄 En cours → on compare aujourd'hui vs date prévue
        $jours = $this->joursEntre(Carbon::today(), $this->date_retour_prevue);

        if ($jours < 0) return abs($jours) . ' jour(s) de retard';
        return $jours . ' jour(s) restant(s)';
    }

    // 🎨 Classe Bootstrap de l'indication
    public function getIndicationClasseAttribute(): string
    {
        if ($this->statut === 'Retourné' && $this->date_retour_effective) {

            $diff = $this->joursEntre(
                $this->date_retour_effective,
                $this->date_retour_prevue
            );

            if ($diff === 0) return 'bg-success';
            if ($diff > 0)   return 'bg-info';
            return 'bg-danger';
        }

        return $this->en_retard ? 'bg-danger' : 'bg-warning text-dark';
    }

    /**
     * Nombre de jours entiers entre deux dates.
     * Positif si $fin est postérieure à $debut.
     */
    private function joursEntre($debut, $fin): int
    {
        $d = $debut->copy()->startOfDay()->getTimestamp();
        $f = $fin->copy()->startOfDay()->getTimestamp();

        return (int) round(($f - $d) / 86400);
    }
}
