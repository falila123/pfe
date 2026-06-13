<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use Illuminate\Support\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        // 📊 KPI (centrés livres & emprunts)
        $totalLivres      = Livre::count();
        $totalExemplaires = Exemplaire::count();
        $disponibles      = Exemplaire::where('statut', 'Disponible')->count();
        $empruntsEnCours  = Emprunt::where('statut', 'En cours')->count();

        $retards = Emprunt::where('statut', 'En cours')
            ->whereDate('date_retour_prevue', '<', Carbon::today())
            ->count();

        // 🥧 Distribution par catégorie (donut + tableau)
        $parCategorie = Livre::query()
            ->whereNotNull('categorie')
            ->selectRaw('categorie, COUNT(*) as total')
            ->groupBy('categorie')
            ->orderByDesc('total')
            ->get();

        // 📈 Emprunts des 8 dernières semaines
        $labels = [];
        $empruntsParSemaine = [];

        for ($i = 7; $i >= 0; $i--) {
            $debut = Carbon::today()->subWeeks($i)->startOfWeek();
            $fin   = (clone $debut)->endOfWeek();

            $labels[] = 'Sem ' . $debut->format('d/m');
            $empruntsParSemaine[] = Emprunt::whereBetween('date_emprunt', [$debut, $fin])->count();
        }

        return view('bibliothecaire.statistiques.index', compact(
            'totalLivres',
            'totalExemplaires',
            'disponibles',
            'empruntsEnCours',
            'retards',
            'parCategorie',
            'labels',
            'empruntsParSemaine'
        ));
    }
}
