<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Livre;
use App\Models\Exemplaire;
use App\Models\Emprunt;

class AdminDashboardController extends Controller
{
    public function index()
{
    // KPI
    $totalLivres = Livre::count();
    $totalExemplaires = Exemplaire::count();
    $activeUsers = User::where('status', 'actif')->count();
    $inactiveUsers = User::where('status', 'inactif')->count();

    // =========================
    // ALERTES RECENTES
    // =========================

    $lastBorrow = Emprunt::with(['livre', 'user'])
        ->where('statut', 'En cours')
        ->latest()
        ->first();

    $lastReturn = Emprunt::with(['livre', 'user'])
        ->where('statut', 'Retourné')
        ->latest()
        ->first();

    $lateCount = Emprunt::where('statut', 'En cours')
        ->whereDate('date_retour_prevue', '<', now())
        ->count();

    $lastUser = User::latest()->first();

    return view('admin.dashboard', compact(
        'totalLivres',
        'totalExemplaires',
        'activeUsers',
        'inactiveUsers',
        'lastBorrow',
        'lastReturn',
        'lateCount',
        'lastUser'
    ));
}
}