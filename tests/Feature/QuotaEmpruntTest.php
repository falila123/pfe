<?php

use App\Models\Demande;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Quota;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    // Quota volontairement bas pour tester la limite : 1 livre pour un externe
    Quota::create(['role' => 'Externe', 'max_livres' => 1, 'max_jours' => 7]);
});

/** Crée un emprunt en cours qui occupe le quota du membre. */
function empruntEnCours(User $membre): void
{
    $livre      = Livre::factory()->create();
    $exemplaire = Exemplaire::factory()->create(['livre_id' => $livre->id, 'statut' => 'Emprunté']);

    Emprunt::create([
        'livre_id'           => $livre->id,
        'user_id'            => $membre->id,
        'exemplaire_id'      => $exemplaire->id,
        'date_emprunt'       => now(),
        'date_retour_prevue' => now()->addDays(7),
        'statut'             => 'En cours',
    ]);
}

it("empêche un membre de soumettre une demande au-delà de son quota", function () {
    $membre = User::factory()->create(['role' => 'Externe', 'status' => 'actif']);
    empruntEnCours($membre); // quota (1) déjà atteint

    $livre = Livre::factory()->create();
    Exemplaire::factory()->create(['livre_id' => $livre->id]);

    $this->actingAs($membre)
        ->from(route('etudiant.catalogue'))
        ->post(route('etudiant.demandes.store'), ['livre_id' => $livre->id])
        ->assertSessionHasErrors('demande');

    // Aucune demande n'a été créée
    expect(Demande::where('user_id', $membre->id)->count())->toBe(0);
});

it("empêche le bibliothécaire de valider une demande au-delà du quota du membre", function () {
    $membre = User::factory()->create(['role' => 'Externe', 'status' => 'actif']);
    $biblio = User::factory()->create(['role' => 'Bibliothécaire', 'status' => 'actif']);
    empruntEnCours($membre); // quota (1) déjà atteint

    $livre      = Livre::factory()->create();
    $exemplaire = Exemplaire::factory()->create(['livre_id' => $livre->id, 'statut' => 'Disponible']);
    $demande    = Demande::create([
        'user_id'  => $membre->id,
        'livre_id' => $livre->id,
        'statut'   => 'En attente',
    ]);

    $this->actingAs($biblio)
        ->from(route('bibliothecaire.demandes.index'))
        ->patch(route('bibliothecaire.demandes.accepter', $demande))
        ->assertSessionHasErrors('demande');

    $demande->refresh();
    $exemplaire->refresh();

    // La demande reste en attente et l'exemplaire n'est pas réservé
    expect($demande->statut)->toBe('En attente')
        ->and($exemplaire->statut)->toBe('Disponible');
});
