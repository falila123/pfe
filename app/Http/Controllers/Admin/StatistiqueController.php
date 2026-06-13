<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StatistiqueController extends Controller
{
    public function index()
    {
        // 📊 KPI (centrés utilisateurs)
        $totalUsers = User::count();
        $actifs     = User::where('status', 'actif')->count();
        $desactives = User::where('status', 'inactif')->count();
        $etudiants  = User::where('role', 'Étudiant')->count();

        // 👥 Répartition par rôle (donut + tableau)
        $parRole = User::query()
            ->selectRaw(
                'role,
                 COUNT(*) as total,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as actifs,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as desactives',
                ['actif', 'inactif']
            )
            ->groupBy('role')
            ->orderByDesc('total')
            ->get();

        return view('admin.statistiques.index', compact(
            'totalUsers',
            'actifs',
            'desactives',
            'etudiants',
            'parRole'
        ));
    }
}
