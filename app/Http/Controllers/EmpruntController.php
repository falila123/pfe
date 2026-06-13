<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\User;
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
            'matricule'     => 'required|string',
            'duree'         => 'required|integer|min:1',
        ], [
            'matricule.required' => 'Veuillez saisir le matricule de l’étudiant.',
            'duree.required'     => 'Veuillez indiquer la durée d’emprunt.',
            'duree.min'          => 'La durée doit être d’au moins 1 jour.',
        ]);

        // ✅ Vérifier l'étudiant
        $etudiant = User::where('matricule', $data['matricule'])
            ->where('role', 'Étudiant')
            ->first();

        if (! $etudiant) {
            return back()
                ->withErrors(['matricule' => 'Matricule étudiant introuvable.'])
                ->withInput();
        }

        $exemplaire = Exemplaire::findOrFail($data['exemplaire_id']);

        // ❌ Empêcher le double emprunt
        if ($exemplaire->statut === 'Emprunté') {
            return back()->withErrors([
                'exemplaire' => 'Cet exemplaire est déjà emprunté.'
            ]);
        }

        // ✅ Création emprunt + passage exemplaire en "Emprunté" (atomique)
        DB::transaction(function () use ($exemplaire, $etudiant, $data) {

            Emprunt::create([
                'livre_id'           => $exemplaire->livre_id,
                'user_id'            => $etudiant->id,
                'exemplaire_id'      => $exemplaire->id,
                'date_emprunt'       => now(),
                'date_retour_prevue' => now()->addDays((int) $data['duree']),
                'statut'             => 'En cours',
            ]);

            $exemplaire->update(['statut' => 'Emprunté']);
        });

        return back()->with('success', "Livre emprunté par {$etudiant->name}.");
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
        $query = Emprunt::with(['livre', 'user', 'exemplaire']);

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
