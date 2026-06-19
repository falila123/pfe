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

        // 👤 Emprunts par sexe de l'emprunteur
        $sexeData = Emprunt::join('users', 'emprunts.user_id', '=', 'users.id')
            ->selectRaw("COALESCE(NULLIF(users.sexe, ''), 'Non renseigné') as sexe, COUNT(*) as total")
            ->groupBy('sexe')
            ->pluck('total', 'sexe');

        $sexeLabels = $sexeData->keys();
        $sexeValues = $sexeData->values();

        // 🏛️ Emprunts internes (Étudiant/Prof/Fonctionnaire) vs externes
        $empruntsInternes = Emprunt::join('users', 'emprunts.user_id', '=', 'users.id')
            ->whereIn('users.role', ['Étudiant', 'Prof', 'Fonctionnaire'])
            ->count();

        $empruntsExternes = Emprunt::join('users', 'emprunts.user_id', '=', 'users.id')
            ->where('users.role', 'Externe')
            ->count();

        return view('bibliothecaire.statistiques.index', compact(
            'totalLivres',
            'totalExemplaires',
            'disponibles',
            'empruntsEnCours',
            'retards',
            'parCategorie',
            'labels',
            'empruntsParSemaine',
            'sexeLabels',
            'sexeValues',
            'empruntsInternes',
            'empruntsExternes'
        ));
    }
}
