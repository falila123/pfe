<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class EmpruntController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->query('statut'); // En cours | En retard | Retourné

        $all = Emprunt::with(['livre.auteurs', 'exemplaire'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // 📊 KPI (sur l'ensemble, via l'accessor en_retard du modèle)
        $enCours   = $all->where('statut', 'En cours')->where('en_retard', false)->count();
        $enRetard  = $all->where('statut', 'En cours')->where('en_retard', true)->count();
        $retournes = $all->where('statut', 'Retourné')->count();

        // 🎚️ Filtre
        $filtered = match ($statut) {
            'En cours'  => $all->where('statut', 'En cours')->where('en_retard', false),
            'En retard' => $all->where('statut', 'En cours')->where('en_retard', true),
            'Retourné'  => $all->where('statut', 'Retourné'),
            default     => $all,
        };
        $filtered = $filtered->values();

        // 📄 Pagination manuelle (10 par page)
        $perPage = 10;
        $page    = Paginator::resolveCurrentPage('page');

        $emprunts = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('etudiant.emprunts.index', compact(
            'emprunts',
            'enCours',
            'enRetard',
            'retournes',
            'statut'
        ));
    }
}
