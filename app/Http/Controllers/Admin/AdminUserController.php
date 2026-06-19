<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {

            $query->where('role', $request->role);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        // Membres = emprunteurs (4 types)
        $membres = User::whereIn('role', ['Étudiant', 'Prof', 'Fonctionnaire', 'Externe'])->count();

        // Personnel = staff
        $personnel = User::whereIn('role', ['Administrateur', 'Bibliothécaire', 'Administration'])->count();

        $desactives = User::where('status', 'inactif')->count();

        $etudiants = User::where('role', 'Étudiant')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'membres',
            'personnel',
            'desactives',
            'etudiants'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'sexe' => ['required', Rule::in(['Homme', 'Femme'])],

            'role' => ['required', Rule::in([
                'Étudiant', 'Prof', 'Fonctionnaire', 'Externe',
                'Bibliothécaire', 'Administrateur', 'Administration',
            ])],

            'matricule'    => 'nullable|unique:users,matricule',
            'telephone'    => 'nullable|string|max:30',
            'numero_piece' => 'nullable|string|max:100',
        ]);

        // Champs obligatoires selon le rôle
        if ($data['role'] === 'Étudiant' && empty($data['matricule'])) {
            return back()
                ->withErrors(['matricule' => 'Le matricule est obligatoire pour un étudiant.'])
                ->withInput();
        }

        if ($data['role'] === 'Externe' && (empty($data['telephone']) || empty($data['numero_piece']))) {
            return back()
                ->withErrors(['numero_piece' => 'Le téléphone et le n° de pièce d’identité sont obligatoires pour un externe.'])
                ->withInput();
        }

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            // 🔐 Mot de passe aléatoire inutilisable : l'utilisateur définit le sien via le lien d'invitation
            'password'     => Hash::make(Str::random(40)),
            'sexe'         => $data['sexe'],
            'role'         => $data['role'],
            'matricule'    => $data['role'] === 'Étudiant' ? $data['matricule'] : null,
            'telephone'    => $data['role'] === 'Externe'  ? $data['telephone'] : null,
            'numero_piece' => $data['role'] === 'Externe'  ? $data['numero_piece'] : null,
            'status'       => 'actif',
            'must_change_password' => false,
        ]);

        // 📧 Lien d'invitation (jeton de réinitialisation) → email
        $token = Password::createToken($user);
        $user->notify(new AccountInvitation($token));

        return back()->with(
            'success',
            "Utilisateur créé ✅ Un email d'invitation a été envoyé à {$user->email} pour qu'il définisse son mot de passe."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, User $user)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'unique:users,email,' . $user->id,
            ],

            'sexe' => ['required', Rule::in(['Homme', 'Femme'])],

            'role' => ['required', Rule::in([
                'Étudiant', 'Prof', 'Fonctionnaire', 'Externe',
                'Bibliothécaire', 'Administrateur', 'Administration',
            ])],

            'matricule' => [
                'nullable',
                'unique:users,matricule,' . $user->id,
            ],
            'telephone'    => 'nullable|string|max:30',
            'numero_piece' => 'nullable|string|max:100',
        ]);

        // Champs obligatoires selon le rôle
        if ($data['role'] === 'Étudiant' && empty($data['matricule'])) {
            return back()
                ->withErrors(['matricule' => 'Le matricule est obligatoire pour un étudiant.'])
                ->withInput();
        }

        if ($data['role'] === 'Externe' && (empty($data['telephone']) || empty($data['numero_piece']))) {
            return back()
                ->withErrors(['numero_piece' => 'Le téléphone et le n° de pièce d’identité sont obligatoires pour un externe.'])
                ->withInput();
        }

        $user->update([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'sexe'         => $data['sexe'],
            'role'         => $data['role'],
            'matricule'    => $data['role'] === 'Étudiant' ? $data['matricule'] : null,
            'telephone'    => $data['role'] === 'Externe'  ? $data['telephone'] : null,
            'numero_piece' => $data['role'] === 'Externe'  ? $data['numero_piece'] : null,
        ]);

        return back()->with(
            'success',
            'Utilisateur mis à jour avec succès.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(User $user)
{
    // On inverse le statut
    $user->status = $user->status === 'actif'
        ? 'inactif'
        : 'actif';

    $user->save();

    // Message selon le nouveau statut
    $message = $user->status === 'actif'
        ? 'Utilisateur activé avec succès.'
        : 'Utilisateur désactivé avec succès.';

    // Type d’alerte Bootstrap
    $status = $user->status === 'actif'
        ? 'success'
        : 'danger';

    return back()->with([
        'message' => $message,
        'status' => $status
    ]);
}
}