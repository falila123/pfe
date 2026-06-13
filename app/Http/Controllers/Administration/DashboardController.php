<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Emprunt;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLivres     = Livre::count();
        $usersActifs     = User::where('status', 'actif')->count();
        $empruntsEnCours = Emprunt::where('statut', 'En cours')->count();

        $retards = Emprunt::where('statut', 'En cours')
            ->whereDate('date_retour_prevue', '<', Carbon::today())
            ->count();

        // 🏆 Top 5 catégories
        $topCategories = Livre::query()
            ->whereNotNull('categorie')
            ->selectRaw('categorie, COUNT(*) as total')
            ->groupBy('categorie')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('administration.dashboard', compact(
            'totalLivres',
            'usersActifs',
            'empruntsEnCours',
            'retards',
            'topCategories'
        ));
    }
}
