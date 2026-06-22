<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Quota;
use App\Notifications\DemandeTraitee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        // 🕒 Expirer les réservations 24h non récupérées (avant affichage)
        Demande::expirerReservationsDepassees();

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
        // ❌ Demande déjà traitée
        if ($demande->statut !== 'En attente') {
            return back()->withErrors([
                'demande' => 'Cette demande a déjà été traitée.'
            ]);
        }

        $membre = $demande->user;

        // ⚖️ Contrôle quota : livres déjà occupés (empruntés + réservés) vs max du type
        $maxLivres = $membre->quotaLivres();
        $occupes   = $membre->nbOccupes();

        if ($occupes >= $maxLivres) {
            return back()->withErrors([
                'demande' => "Quota atteint : {$membre->name} occupe déjà {$occupes} livre(s) (max {$maxLivres} pour un {$membre->roleLabel()})."
            ]);
        }

        // ✅ Exemplaire disponible
        $exemplaire = Exemplaire::where('livre_id', $demande->livre_id)
            ->where('statut', 'Disponible')
            ->first();

        if (! $exemplaire) {
            return back()->withErrors([
                'demande' => 'Aucun exemplaire disponible pour ce livre. Impossible de valider.'
            ]);
        }

        // ✅ Réservation 24h (atomique) - l'emprunt sera créé au retrait
        DB::transaction(function () use ($demande, $exemplaire) {

            $exemplaire->update(['statut' => 'Réservé']);

            $demande->update([
                'statut'              => 'Acceptée',
                'exemplaire_id'       => $exemplaire->id,
                'date_limite_retrait' => now()->addHours(24),
            ]);
        });

        // 🔔 Notifier le membre
        $demande->refresh();
        $demande->user->notify(new DemandeTraitee($demande));

        return back()->with(
            'success',
            "Demande acceptée ✅ Exemplaire {$exemplaire->code} réservé - à récupérer avant le "
                . $demande->date_limite_retrait->format('d/m/Y à H:i') . "."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REMETTRE LE LIVRE (retrait)  → la réservation devient un emprunt actif
    |--------------------------------------------------------------------------
    */
    public function remettre(Request $request, Demande $demande)
    {
        // ❌ Doit être une réservation en attente de retrait
        if ($demande->statut !== 'Acceptée') {
            return back()->withErrors([
                'demande' => "Cette demande n'est pas une réservation à remettre."
            ]);
        }

        $exemplaire = $demande->exemplaire;

        if (! $exemplaire || $exemplaire->statut !== 'Réservé') {
            return back()->withErrors([
                'demande' => "L'exemplaire réservé n'est plus disponible. Impossible de finaliser le retrait."
            ]);
        }

        $membre   = $demande->user;
        $maxJours = $membre->quotaJours();

        // ✅ Création emprunt + exemplaire "Emprunté" + demande "Récupérée" (atomique)
        DB::transaction(function () use ($demande, $exemplaire, $membre, $maxJours) {

            Emprunt::create([
                'livre_id'           => $exemplaire->livre_id,
                'user_id'            => $membre->id,
                'demande_id'         => $demande->id,
                'exemplaire_id'      => $exemplaire->id,
                'date_emprunt'       => now(),
                'date_retour_prevue' => now()->addDays($maxJours),
                'statut'             => 'En cours',
            ]);

            $exemplaire->update(['statut' => 'Emprunté']);

            $demande->update(['statut' => 'Récupérée']);
        });

        // 🔔 Notifier le membre
        $demande->refresh();
        $demande->user->notify(new DemandeTraitee($demande));

        return back()->with(
            'success',
            "Retrait confirmé pour {$membre->name} ✅ Emprunt en cours - retour prévu dans {$maxJours} jours."
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
