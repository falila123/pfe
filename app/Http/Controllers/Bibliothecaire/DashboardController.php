<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use App\Models\Demande;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 📊 KPI
        $totalBooks     = Livre::count();
        $availableBooks = Exemplaire::where('statut', 'Disponible')->count();
        $borrowedBooks  = Exemplaire::where('statut', 'Emprunté')->count();

        $lateBooks = Emprunt::where('statut', 'En cours')
            ->whereDate('date_retour_prevue', '<', Carbon::today())
            ->count();

        $demandesEnAttente = Demande::where('statut', 'En attente')->count();

        // 🔔 Activité récente
        $dernierEmprunt = Emprunt::with(['livre', 'user'])
            ->where('statut', 'En cours')
            ->latest()
            ->first();

        $dernierRetour = Emprunt::with(['livre', 'user'])
            ->where('statut', 'Retourné')
            ->latest('date_retour_effective')
            ->first();

        return view('bibliothecaire.dashboard', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'lateBooks',
            'demandesEnAttente',
            'dernierEmprunt',
            'dernierRetour'
        ));
    }
}
