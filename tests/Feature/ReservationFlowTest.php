<?php

use App\Models\Demande;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Quota;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

function membre(string $role = 'Étudiant'): User
{
    return User::factory()->create(['role' => $role, 'status' => 'actif']);
}

function bibliothecaire(): User
{
    return User::factory()->create(['role' => 'Bibliothécaire', 'status' => 'actif']);
}

beforeEach(function () {
    Notification::fake();
    Quota::create(['role' => 'Étudiant', 'max_livres' => 3, 'max_jours' => 14]);
});

it("permet à un membre de soumettre une demande d'emprunt", function () {
    $membre = membre();
    $livre  = Livre::factory()->create();
    Exemplaire::factory()->create(['livre_id' => $livre->id]);

    $this->actingAs($membre)
        ->post(route('etudiant.demandes.store'), ['livre_id' => $livre->id]);

    expect(
        Demande::where('user_id', $membre->id)
            ->where('livre_id', $livre->id)
            ->where('statut', 'En attente')
            ->exists()
    )->toBeTrue();
});

it("réserve un exemplaire pour 24 heures lorsqu'une demande est acceptée", function () {
    $membre     = membre();
    $biblio     = bibliothecaire();
    $livre      = Livre::factory()->create();
    $exemplaire = Exemplaire::factory()->create(['livre_id' => $livre->id, 'statut' => 'Disponible']);
    $demande    = Demande::create([
        'user_id'  => $membre->id,
        'livre_id' => $livre->id,
        'statut'   => 'En attente',
    ]);

    $this->actingAs($biblio)
        ->patch(route('bibliothecaire.demandes.accepter', $demande));

    $demande->refresh();
    $exemplaire->refresh();

    expect($demande->statut)->toBe('Acceptée')
        ->and($demande->exemplaire_id)->toBe($exemplaire->id)
        ->and($demande->date_limite_retrait)->not->toBeNull()
        ->and($exemplaire->statut)->toBe('Réservé');
});

it("crée un emprunt et passe l'exemplaire en « Emprunté » lors du retrait", function () {
    $membre     = membre();
    $biblio     = bibliothecaire();
    $livre      = Livre::factory()->create();
    $exemplaire = Exemplaire::factory()->create(['livre_id' => $livre->id, 'statut' => 'Réservé']);
    $demande    = Demande::create([
        'user_id'             => $membre->id,
        'livre_id'            => $livre->id,
        'exemplaire_id'       => $exemplaire->id,
        'statut'              => 'Acceptée',
        'date_limite_retrait' => now()->addHours(24),
    ]);

    $this->actingAs($biblio)
        ->patch(route('bibliothecaire.demandes.remettre', $demande));

    $demande->refresh();
    $exemplaire->refresh();

    expect($demande->statut)->toBe('Récupérée')
        ->and($exemplaire->statut)->toBe('Emprunté')
        ->and(
            Emprunt::where('demande_id', $demande->id)
                ->where('statut', 'En cours')
                ->exists()
        )->toBeTrue();
});

it("libère l'exemplaire lors du retour d'un emprunt", function () {
    $membre     = membre();
    $biblio     = bibliothecaire();
    $livre      = Livre::factory()->create();
    $exemplaire = Exemplaire::factory()->create(['livre_id' => $livre->id, 'statut' => 'Emprunté']);
    $emprunt    = Emprunt::create([
        'livre_id'           => $livre->id,
        'user_id'            => $membre->id,
        'exemplaire_id'      => $exemplaire->id,
        'date_emprunt'       => now(),
        'date_retour_prevue' => now()->addDays(14),
        'statut'             => 'En cours',
    ]);

    $this->actingAs($biblio)
        ->patch(route('bibliothecaire.emprunts.retour', $exemplaire));

    $emprunt->refresh();
    $exemplaire->refresh();

    expect($exemplaire->statut)->toBe('Disponible')
        ->and($emprunt->statut)->toBe('Retourné')
        ->and($emprunt->date_retour_effective)->not->toBeNull();
});

it("expire automatiquement une réservation non retirée dans les délais et libère l'exemplaire", function () {
    $membre     = membre();
    $livre      = Livre::factory()->create();
    $exemplaire = Exemplaire::factory()->create(['livre_id' => $livre->id, 'statut' => 'Réservé']);
    $demande    = Demande::create([
        'user_id'             => $membre->id,
        'livre_id'            => $livre->id,
        'exemplaire_id'       => $exemplaire->id,
        'statut'              => 'Acceptée',
        'date_limite_retrait' => now()->subHour(), // délai dépassé
    ]);

    $count = Demande::expirerReservationsDepassees();

    $demande->refresh();
    $exemplaire->refresh();

    expect($count)->toBe(1)
        ->and($demande->statut)->toBe('Expirée')
        ->and($exemplaire->statut)->toBe('Disponible');
});
