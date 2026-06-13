<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Demande;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Livre::with(['auteurs', 'exemplaires'])
            ->withCount([
                'exemplaires as nb_disponibles' => function ($q) {
                    $q->where('statut', 'Disponible');
                }
            ]);

        // 🔎 Recherche (titre ou auteur)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhereHas('auteurs', function ($a) use ($search) {
                      $a->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // 🎚️ Filtre catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $livres = $query->latest()->get();

        // ✅ Livres déjà demandés (en attente) par l'étudiant connecté
        $demandesEnAttente = Demande::where('user_id', auth()->id())
            ->where('statut', 'En attente')
            ->pluck('livre_id')
            ->toArray();

        // Pour le filtre catégorie
        $categories = Livre::query()
            ->select('categorie')
            ->whereNotNull('categorie')
            ->distinct()
            ->pluck('categorie');

        return view('etudiant.catalogue.index', compact(
            'livres',
            'demandesEnAttente',
            'categories'
        ));
    }
}
