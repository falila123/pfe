<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use App\Models\User;
use Illuminate\Support\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        // 📊 KPI — vue d'ensemble structurelle (volumes)
        $adherents        = User::whereIn('role', User::ROLES_MEMBRES)->count();
        $totalLivres      = Livre::count();
        $totalExemplaires = Exemplaire::count();
        $totalEmprunts    = Emprunt::count();

        // 🥧 Donut : répartition par catégorie
        $parCategorie = Livre::query()
            ->whereNotNull('categorie')
            ->selectRaw('categorie, COUNT(*) as total')
            ->groupBy('categorie')
            ->orderByDesc('total')
            ->get();

        // 🥧 Donut : répartition des utilisateurs par rôle
        $parRole = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
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

        // ⚧ Emprunts par sexe
        $sexeData = Emprunt::join('users', 'emprunts.user_id', '=', 'users.id')
            ->selectRaw("COALESCE(NULLIF(users.sexe, ''), 'Non renseigné') as sexe, COUNT(*) as total")
            ->groupBy('sexe')
            ->pluck('total', 'sexe');
        $sexeLabels = $sexeData->keys();
        $sexeValues = $sexeData->values();

        // 🏛️ Emprunts internes vs externes
        $empruntsInternes = Emprunt::join('users', 'emprunts.user_id', '=', 'users.id')
            ->whereIn('users.role', ['Étudiant', 'Prof', 'Fonctionnaire'])->count();
        $empruntsExternes = Emprunt::join('users', 'emprunts.user_id', '=', 'users.id')
            ->where('users.role', 'Externe')->count();

        return view('administration.statistiques.index', compact(
            'adherents',
            'totalLivres',
            'totalExemplaires',
            'totalEmprunts',
            'parCategorie',
            'parRole',
            'labels',
            'empruntsParSemaine',
            'sexeLabels',
            'sexeValues',
            'empruntsInternes',
            'empruntsExternes'
        ));
    }
}
