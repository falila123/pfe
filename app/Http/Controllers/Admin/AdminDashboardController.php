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
    // KPI orientés comptes (domaine de l'administrateur)
    $internes      = User::whereIn('role', ['Étudiant', 'Prof', 'Fonctionnaire'])->count();
    $externes      = User::where('role', 'Externe')->count();
    $activeUsers   = User::where('status', 'actif')->count();
    $inactiveUsers = User::where('status', 'inactif')->count();

    // =========================
    // ACTIVITÉ RÉCENTE (comptes)
    // =========================

    $lastUser        = User::latest()->first();
    $lastStaff       = User::whereIn('role', User::ROLES_PERSONNEL)->latest()->first();
    $lastDeactivated = User::where('status', 'inactif')->latest('updated_at')->first();

    return view('admin.dashboard', compact(
        'internes',
        'externes',
        'activeUsers',
        'inactiveUsers',
        'lastUser',
        'lastStaff',
        'lastDeactivated'
    ));
}
}