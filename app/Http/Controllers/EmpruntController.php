<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\User;
use App\Models\Quota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER UN EMPRUNT  (équivalent confirmBorrow)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'exemplaire_id' => 'required|exists:exemplaires,id',
            'user_id'       => 'required|exists:users,id',
        ]);

        // ✅ Le membre doit être un emprunteur
        $membre = User::whereIn('role', ['Étudiant', 'Prof', 'Fonctionnaire', 'Externe'])
            ->find($data['user_id']);

        if (! $membre) {
            return back()->withErrors(['user_id' => 'Membre emprunteur invalide.']);
        }

        $exemplaire = Exemplaire::findOrFail($data['exemplaire_id']);

        // ❌ Empêcher le double emprunt
        if ($exemplaire->statut === 'Emprunté') {
            return back()->withErrors(['exemplaire' => 'Cet exemplaire est déjà emprunté.']);
        }

        // ⚖️ Quota du type de membre + contrôle des livres occupés (empruntés + réservés)
        $maxLivres = $membre->quotaLivres();
        $maxJours  = $membre->quotaJours();
        $occupes   = $membre->nbOccupes();

        if ($occupes >= $maxLivres) {
            return back()->withErrors([
                'user_id' => "Quota atteint : {$membre->name} occupe déjà {$occupes} livre(s) (max {$maxLivres} pour un {$membre->role})."
            ]);
        }

        // ✅ Création emprunt + passage exemplaire en "Emprunté" (atomique), durée = quota
        DB::transaction(function () use ($exemplaire, $membre, $maxJours) {

            Emprunt::create([
                'livre_id'           => $exemplaire->livre_id,
                'user_id'            => $membre->id,
                'exemplaire_id'      => $exemplaire->id,
                'date_emprunt'       => now(),
                'date_retour_prevue' => now()->addDays($maxJours),
                'statut'             => 'En cours',
            ]);

            $exemplaire->update(['statut' => 'Emprunté']);
        });

        return back()->with(
            'success',
            "Livre emprunté par {$membre->name} — retour prévu dans {$maxJours} jours."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RETOURNER UN EXEMPLAIRE  (équivalent returnBook)
    |--------------------------------------------------------------------------
    */
    public function retour(Exemplaire $exemplaire)
    {
        if ($exemplaire->statut !== 'Emprunté') {
            return back()->withErrors([
                'exemplaire' => 'Cet exemplaire est déjà disponible.'
            ]);
        }

        $emprunt = $exemplaire->emprunts()
            ->where('statut', 'En cours')
            ->latest()
            ->first();

        DB::transaction(function () use ($exemplaire, $emprunt) {

            if ($emprunt) {
                $emprunt->update([
                    'statut'                => 'Retourné',
                    'date_retour_effective' => now(),
                ]);
            }

            $exemplaire->update(['statut' => 'Disponible']);
        });

        return back()->with('success', 'Livre retourné avec succès.');
    }

    /*
    |--------------------------------------------------------------------------
    | SUIVI DES EMPRUNTS  (page précédente)
    |--------------------------------------------------------------------------
    */
    public function suivi(Request $request)
    {
        $query = Emprunt::with(['livre', 'user', 'exemplaire', 'demande']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('matricule', 'like', "%{$search}%");
                })
                ->orWhereHas('livre', function ($l) use ($search) {
                    $l->where('titre', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('statut')) {
            $today = Carbon::today();

            if ($request->statut === 'En cours') {
                $query->where('statut', 'En cours')
                      ->whereDate('date_retour_prevue', '>=', $today);
            } elseif ($request->statut === 'En retard') {
                $query->where('statut', 'En cours')
                      ->whereDate('date_retour_prevue', '<', $today);
            } elseif ($request->statut === 'Retourné') {
                $query->where('statut', 'Retourné');
            }
        }

        $emprunts = $query->latest()->get();

        $today = Carbon::today();

        $totalEmprunts     = Emprunt::count();
        $empruntsRetournes = Emprunt::where('statut', 'Retourné')->count();
        $empruntsRetard    = Emprunt::where('statut', 'En cours')
            ->whereDate('date_retour_prevue', '<', $today)->count();
        $empruntsEnCours   = Emprunt::where('statut', 'En cours')
            ->whereDate('date_retour_prevue', '>=', $today)->count();

        return view('bibliothecaire.emprunts.index', compact(
            'emprunts', 'totalEmprunts', 'empruntsEnCours',
            'empruntsRetard', 'empruntsRetournes'
        ));
    }
}
