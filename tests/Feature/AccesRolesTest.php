<?php

use App\Models\User;

it("redirige un visiteur non authentifié vers la page de connexion", function () {
    $this->get(route('bibliothecaire.livres.index'))
        ->assertRedirect(route('login'));
});

it("interdit à un membre l'accès à l'espace bibliothécaire", function () {
    $membre = User::factory()->create(['role' => 'Étudiant', 'status' => 'actif']);

    $this->actingAs($membre)
        ->get(route('bibliothecaire.livres.index'))
        ->assertForbidden();
});

it("interdit à un membre l'accès à l'espace administrateur", function () {
    $membre = User::factory()->create(['role' => 'Étudiant', 'status' => 'actif']);

    $this->actingAs($membre)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

it("interdit au personnel l'accès à l'espace membre", function () {
    $biblio = User::factory()->create(['role' => 'Bibliothécaire', 'status' => 'actif']);

    $this->actingAs($biblio)
        ->get(route('etudiant.catalogue'))
        ->assertForbidden();
});
