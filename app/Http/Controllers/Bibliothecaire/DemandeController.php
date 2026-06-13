<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Notifications\DemandeTraitee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Demande::with(['user', 'livre.auteurs']);

        // 🎚️ Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $demandes = $query->latest()->get();

        // 📊 KPI
        $enAttente = Demande::where('statut', 'En attente')->count();
        $acceptees = Demande::where('statut', 'Acceptée')->count();
        $refusees  = Demande::where('statut', 'Refusée')->count();

        return view('bibliothecaire.demandes.index', compact(
            'demandes',
            'enAttente',
            'acceptees',
            'refusees'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDER UNE DEMANDE  → crée l'emprunt
    |--------------------------------------------------------------------------
    */
    public function accepter(Request $request, Demande $demande)
    {
        $data = $request->validate([
            'duree' => 'required|integer|min:1',
        ], [
            'duree.required' => 'Veuillez indiquer la durée d’emprunt.',
            'duree.min'      => 'La durée doit être d’au moins 1 jour.',
        ]);

        // ❌ Demande déjà traitée
        if ($demande->statut !== 'En attente') {
            return back()->withErrors([
                'demande' => 'Cette demande a déjà été traitée.'
            ]);
        }

        // ✅ Attribution automatique d'un exemplaire disponible
        $exemplaire = Exemplaire::where('livre_id', $demande->livre_id)
            ->where('statut', 'Disponible')
            ->first();

        if (! $exemplaire) {
            return back()->withErrors([
                'demande' => 'Aucun exemplaire disponible pour ce livre. Impossible de valider.'
            ]);
        }

        // ✅ Création emprunt + maj exemplaire + maj demande (atomique)
        DB::transaction(function () use ($demande, $exemplaire, $data) {

            Emprunt::create([
                'livre_id'           => $demande->livre_id,
                'user_id'            => $demande->user_id,
                'exemplaire_id'      => $exemplaire->id,
                'date_emprunt'       => now(),
                'date_retour_prevue' => now()->addDays((int) $data['duree']),
                'statut'             => 'En cours',
            ]);

            $exemplaire->update(['statut' => 'Emprunté']);

            $demande->update(['statut' => 'Acceptée']);
        });

        // 🔔 Notifier l'étudiant
        $demande->user->notify(new DemandeTraitee($demande));

        return back()->with(
            'success',
            "Demande acceptée ✅ L'emprunt a été créé (exemplaire {$exemplaire->code})."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REFUSER UNE DEMANDE  (motif optionnel)
    |--------------------------------------------------------------------------
    */
    public function refuser(Request $request, Demande $demande)
    {
        $data = $request->validate([
            'motif_refus' => 'nullable|string|max:1000',
        ]);

        // ❌ Demande déjà traitée
        if ($demande->statut !== 'En attente') {
            return back()->withErrors([
                'demande' => 'Cette demande a déjà été traitée.'
            ]);
        }

        $demande->update([
            'statut'      => 'Refusée',
            'motif_refus' => $data['motif_refus'] ?? null,
        ]);

        // 🔔 Notifier l'étudiant
        $demande->user->notify(new DemandeTraitee($demande));

        return back()->with('success', 'Demande refusée.');
    }
}
