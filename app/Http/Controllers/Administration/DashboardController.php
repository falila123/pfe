<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Demande;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI décisionnels (coup d'œil opérationnel)
        $empruntsEnCours = Emprunt::where('statut', 'En cours')->count();

        $retards = Emprunt::where('statut', 'En cours')
            ->whereDate('date_retour_prevue', '<', Carbon::today())
            ->count();

        $demandesEnAttente = Demande::where('statut', 'En attente')->count();

        $totalExemplaires = Exemplaire::count();
        $dispoExemplaires = Exemplaire::where('statut', 'Disponible')->count();
        $tauxDispo = $totalExemplaires > 0
            ? round($dispoExemplaires / $totalExemplaires * 100)
            : 0;

        // 🏆 Top 5 catégories
        $topCategories = Livre::query()
            ->whereNotNull('categorie')
            ->selectRaw('categorie, COUNT(*) as total')
            ->groupBy('categorie')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('administration.dashboard', compact(
            'empruntsEnCours',
            'retards',
            'demandesEnAttente',
            'tauxDispo',
            'topCategories'
        ));
    }
}
