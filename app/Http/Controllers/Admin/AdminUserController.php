<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $etudiants = User::where('role', 'Étudiant')->count();

        $bibliothecaires = User::where('role', 'Bibliothécaire')->count();

        $administrateurs = User::where('role', 'Administrateur')->count();

        $administration = User::where('role', 'Administration')->count();

        return view('admin.users.index', compact(
            'users',
            'etudiants',
            'bibliothecaires',
            'administrateurs',
            'administration'
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

            'password' => 'required|min:4',

            'role' => 'required',

            'matricule' => 'nullable|unique:users,matricule',
        ]);

        /*
        |--------------------------------------------------------------------------
        | MATRICULE REQUIRED FOR STUDENT
        |--------------------------------------------------------------------------
        */

        if (
            $data['role'] === 'Étudiant'
            && empty($data['matricule'])
        ) {

            return back()
                ->withErrors([
                    'matricule' => 'Le matricule est obligatoire pour un étudiant.'
                ])
                ->withInput();
        }

        User::create([

            'name' => $data['name'],

            'email' => $data['email'],

            'password' => Hash::make($data['password']),

            'role' => $data['role'],

            'matricule' => $data['role'] === 'Étudiant'
                ? $data['matricule']
                : null,

            'status' => 'actif',
        ]);

        return back()->with(
            'success',
            'Utilisateur ajouté avec succès.'
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

                'role' => 'required',

                'matricule' => [
                    'nullable',
                    'unique:users,matricule,' . $user->id,
                ],
            ]);

        /*
        |--------------------------------------------------------------------------
        | MATRICULE REQUIRED FOR STUDENT
        |--------------------------------------------------------------------------
        */

        if (
            $data['role'] === 'Étudiant'
            && empty($data['matricule'])
        ) {

            return back()
                ->withErrors([
                    'matricule' => 'Le matricule est obligatoire pour un étudiant.'
                ])
                ->withInput();
        }

        $user->update([

            'name' => $data['name'],

            'email' => $data['email'],

            'role' => $data['role'],

            'matricule' => $data['role'] === 'Étudiant'
                ? $data['matricule']
                : null,
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