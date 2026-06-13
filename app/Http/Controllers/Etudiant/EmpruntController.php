<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;

class EmpruntController extends Controller
{
    public function index()
    {
        $emprunts = Emprunt::with(['livre.auteurs', 'exemplaire'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // 📊 KPI (on réutilise l'accessor en_retard du modèle Emprunt)
        $enCours   = $emprunts->where('statut', 'En cours')->where('en_retard', false)->count();
        $enRetard  = $emprunts->where('statut', 'En cours')->where('en_retard', true)->count();
        $retournes = $emprunts->where('statut', 'Retourné')->count();

        return view('etudiant.emprunts.index', compact(
            'emprunts',
            'enCours',
            'enRetard',
            'retournes'
        ));
    }
}
