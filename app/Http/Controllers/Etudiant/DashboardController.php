<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use App\Models\Demande;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Emprunts de l'étudiant (servent aux KPI + à l'aperçu)
        $emprunts = Emprunt::with('livre')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // 📊 KPI
        $empruntsEnCours = $emprunts->where('statut', 'En cours')->where('en_retard', false)->count();
        $empruntsRetard  = $emprunts->where('statut', 'En cours')->where('en_retard', true)->count();
        $retournes       = $emprunts->where('statut', 'Retourné')->count();

        $demandesEnAttente = Demande::where('user_id', $userId)
            ->where('statut', 'En attente')
            ->count();

        // 👁️ Résumé : emprunts à rendre le plus tôt (3 max)
        $aRendre = $emprunts->where('statut', 'En cours')
            ->sortBy('date_retour_prevue')
            ->take(3)
            ->values();

        // 👁️ Résumé : la toute dernière demande effectuée
        $derniereDemande = Demande::with('livre')
            ->where('user_id', $userId)
            ->latest()
            ->first();

        return view('etudiant.dashboard', compact(
            'empruntsEnCours',
            'empruntsRetard',
            'retournes',
            'demandesEnAttente',
            'aRendre',
            'derniereDemande'
        ));
    }
}
