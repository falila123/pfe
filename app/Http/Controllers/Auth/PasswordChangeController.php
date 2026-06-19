<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    // Formulaire de changement de mot de passe forcé
    public function show()
    {
        return view('auth.change-password');
    }

    // Enregistrement du nouveau mot de passe
    public function update(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required'  => 'Veuillez saisir un nouveau mot de passe.',
            'password.confirmed' => 'La confirmation ne correspond pas.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = $request->user();

        $user->update([
            'password'             => Hash::make($data['password']),
            'must_change_password' => false,
        ]);

        // Redirection vers le tableau de bord selon le rôle (même logique que la connexion)
        $route = match ($user->role) {
            'Administrateur'                                => 'admin.dashboard',
            'Bibliothécaire'                                => 'bibliothecaire.dashboard',
            'Étudiant', 'Prof', 'Fonctionnaire', 'Externe' => 'etudiant.dashboard',
            'Administration'                                => 'administration.dashboard',
            default                                         => null,
        };

        $redirect = $route ? redirect()->route($route) : redirect('/');

        return $redirect->with('success', 'Mot de passe mis à jour. Bienvenue !');
    }
}
