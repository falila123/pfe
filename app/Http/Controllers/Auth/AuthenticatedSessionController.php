<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Page de connexion MEMBRES (entrée publique).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Page de connexion PERSONNEL (back-office, entrée discrète).
     */
    public function createPersonnel(): View
    {
        return view('auth.login-personnel');
    }

    /**
     * Connexion MEMBRES : seuls les emprunteurs sont autorisés ici.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();

        if (! $user->estMembre()) {
            $this->logoutNow($request);

            return redirect()->route('personnel.login')->withErrors([
                'email' => "Ce compte appartient au personnel. Veuillez utiliser l'espace personnel.",
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /**
     * Connexion PERSONNEL : seuls les comptes staff sont autorisés ici.
     */
    public function storePersonnel(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();

        if (! $user->estPersonnel()) {
            $this->logoutNow($request);

            return redirect()->route('login')->withErrors([
                'email' => "Ce compte est un compte membre. Veuillez utiliser l'espace membre.",
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // On mémorise la catégorie avant déconnexion pour revenir à la bonne entrée
        $estPersonnel = auth()->check() && auth()->user()->estPersonnel();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route($estPersonnel ? 'personnel.login' : 'login');
    }

    /**
     * Redirection vers le tableau de bord selon le rôle.
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'Administrateur'                                => redirect()->route('admin.dashboard'),
            'Bibliothécaire'                                => redirect()->route('bibliothecaire.dashboard'),
            'Étudiant', 'Prof', 'Fonctionnaire', 'Externe' => redirect()->route('etudiant.dashboard'),
            'Administration'                                => redirect()->route('administration.dashboard'),
            default                                         => redirect('/'),
        };
    }

    /**
     * Déconnexion immédiate (mauvaise entrée).
     */
    private function logoutNow(Request $request): void
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
