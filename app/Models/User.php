<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // 👥 Catégories de rôles (séparation des espaces de connexion)
    public const ROLES_MEMBRES   = ['Étudiant', 'Prof', 'Fonctionnaire', 'Externe'];
    public const ROLES_PERSONNEL = ['Administrateur', 'Bibliothécaire', 'Administration'];

    public function estMembre(): bool
    {
        return in_array($this->role, self::ROLES_MEMBRES, true);
    }

    public function estPersonnel(): bool
    {
        return in_array($this->role, self::ROLES_PERSONNEL, true);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'matricule',
    'telephone',
    'numero_piece',
    'sexe',
    'status',
    'must_change_password',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // 🔑 Email de réinitialisation personnalisé (français)
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordLink($token));
    }

    public function emprunts()
{
    return $this->hasMany(Emprunt::class);
}
public function demandes()
{
    return $this->hasMany(Demande::class);
}

    /*
    |--------------------------------------------------------------------------
    | Quota & compteurs d'emprunt (source de vérité unique)
    |--------------------------------------------------------------------------
    */

    // Nombre max de livres autorisés selon le type (quota)
    public function quotaLivres(): int
    {
        return (int) (Quota::where('role', $this->role)->value('max_livres') ?? 1);
    }

    // Durée d'emprunt (jours) selon le type (quota)
    public function quotaJours(): int
    {
        return (int) (Quota::where('role', $this->role)->value('max_jours') ?? 7);
    }

    // Livres physiquement occupés : empruntés (En cours) + réservés en attente de retrait
    public function nbOccupes(): int
    {
        $emprunts = $this->emprunts()->where('statut', 'En cours')->count();

        $reservations = $this->demandes()
            ->where('statut', 'Acceptée')
            ->whereNotNull('date_limite_retrait')   // ⚠️ exclut les anciennes "Acceptée" (sans réservation)
            ->count();

        return $emprunts + $reservations;
    }

    // Demandes en attente de traitement par le bibliothécaire
    public function nbEnAttente(): int
    {
        return $this->demandes()->where('statut', 'En attente')->count();
    }

}
