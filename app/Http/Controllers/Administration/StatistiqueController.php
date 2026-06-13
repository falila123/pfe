<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use App\Models\User;

class StatistiqueController extends Controller
{
    public function index()
    {
        // 📊 KPI — vue d'ensemble (fonds + utilisateurs)
        $totalLivres      = Livre::count();
        $totalExemplaires = Exemplaire::count();
        $usersActifs      = User::where('status', 'actif')->count();
        $empruntsEnCours  = Emprunt::where('statut', 'En cours')->count();

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

        return view('administration.statistiques.index', compact(
            'totalLivres',
            'totalExemplaires',
            'usersActifs',
            'empruntsEnCours',
            'parCategorie',
            'parRole'
        ));
    }
}
