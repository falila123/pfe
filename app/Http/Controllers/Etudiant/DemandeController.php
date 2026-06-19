<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        // 🕒 Expirer les réservations 24h non récupérées (avant affichage)
        Demande::expirerReservationsDepassees();

        $statut = $request->query('statut');

        // 📊 KPI (sur l'ensemble, indépendamment du filtre)
        $base      = Demande::where('user_id', auth()->id());
        $enAttente = (clone $base)->where('statut', 'En attente')->count();
        $acceptees = (clone $base)->where('statut', 'Acceptée')->count();
        $refusees  = (clone $base)->where('statut', 'Refusée')->count();

        // 🎚️ Liste filtrée + paginée
        $demandes = Demande::with('livre.auteurs')
            ->where('user_id', auth()->id())
            ->when($statut, fn ($q) => $q->where('statut', $statut))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('etudiant.demandes.index', compact(
            'demandes',
            'enAttente',
            'acceptees',
            'refusees',
            'statut'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'livre_id' => 'required|exists:livres,id',
        ]);

        $membre = auth()->user();

        // ⛔ Limite atteinte : livres occupés (empruntés + réservés) + demandes en attente
        $maxLivres = $membre->quotaLivres();
        $engages   = $membre->nbOccupes() + $membre->nbEnAttente();

        if ($engages >= $maxLivres) {
            return back()->withErrors([
                'demande' => "Vous avez atteint votre limite d'emprunts ({$engages}/{$maxLivres}). Réessayez plus tard, une fois un livre rendu."
            ]);
        }

        // ❌ Empêcher une 2ᵉ demande en attente pour le même livre
        $existe = Demande::where('user_id', $membre->id)
            ->where('livre_id', $data['livre_id'])
            ->where('statut', 'En attente')
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'demande' => 'Vous avez déjà une demande en attente pour ce livre.'
            ]);
        }

        Demande::create([
            'user_id'  => auth()->id(),
            'livre_id' => $data['livre_id'],
            'statut'   => 'En attente',
        ]);

        return back()->with(
            'success',
            'Demande envoyée ✅ Elle est en attente de validation par le bibliothécaire.'
        );
    }

    public function destroy(Demande $demande)
    {
        // 🔒 Sécurité : la demande doit appartenir à l'étudiant connecté
        if ($demande->user_id !== auth()->id()) {
            abort(403);
        }

        // 🔒 On n'annule qu'une demande encore "En attente"
        if ($demande->statut !== 'En attente') {
            return back()->withErrors([
                'demande' => 'Cette demande ne peut plus être annulée.'
            ]);
        }

        $demande->delete();

        return back()->with('success', 'Demande annulée.');
    }
}
