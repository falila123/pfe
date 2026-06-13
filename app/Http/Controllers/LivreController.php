<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Exemplaire;
use Illuminate\Validation\Rule;
use App\Models\User;


class LivreController extends Controller
{
    public function create()
    {
        return view('livres.create');
    }

public function store(Request $request)
{
  $data = $request->validate(
[
    'titre' => 'required|string',

    'isbn' => [
        'required',
        'string',
        'regex:/^\d{10}$/',
    ],

    'categorie' => 'nullable|string',
    'sous_categorie_dewey' => 'nullable|string',
    'type_livre' => 'nullable|string',
    'langue' => 'nullable|string',
    'description' => 'nullable|string',
    'auteurs' => 'required|array',
    'auteurs.*' => 'exists:auteurs,id',
    'couverture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
],
[
    'isbn.required' => 'Veuillez saisir un ISBN.',
    'isbn.regex' => 'L’ISBN saisi est invalide. Veuillez entrer exactement 10 chiffres.',
]
);

    // Reconstruction de l'ISBN complet
    $data['isbn'] = '978' . $data['isbn'];

    // Vérification unicité
    if (Livre::where('isbn', $data['isbn'])->exists()) {
        return back()
            ->withInput()
            ->withErrors([
                'isbn' => 'Cet ISBN existe déjà.'
            ]);
    }

    // numéro du livre par catégorie
    $numeroLivre = $this->getNextNumeroLivre($data['categorie']);

    // génération de la cote finale
    $cote = $this->generateCote($data, $numeroLivre);

    // gestion image couverture
    $couverturePath = null;

    if ($request->hasFile('couverture')) {
        $couverturePath = $request->file('couverture')
            ->store('couvertures', 'public');
    }

    // création livre
    $livre = Livre::create([
        'titre' => $data['titre'],
        'cote' => $cote,
        'isbn' => $data['isbn'],
        'categorie' => $data['categorie'] ?? null,
        'sous_categorie_dewey' => $data['sous_categorie_dewey'] ?? null,
        'type_livre' => $data['type_livre'] ?? null,
        'langue' => $data['langue'] ?? null,
        'description' => $data['description'] ?? null,
        'couverture' => $couverturePath,
    ]);

    // exemplaire initial
    Exemplaire::create([
        'livre_id' => $livre->id,
        'code' => $livre->cote . '-01',
        'statut' => 'Disponible',
    ]);

    // auteurs
    $livre->auteurs()->attach($data['auteurs']);

    return redirect()
        ->route('bibliothecaire.livres.index')
        ->with('success', 'Livre ajouté avec succès');
}

public function update(Request $request, Livre $livre)
{
    $data = $request->validate(
[
    'titre' => 'required|string',

    'isbn' => [
        'required',
        'string',
        'regex:/^\d{10}$/',
    ],

    'categorie' => 'nullable|string',
    'sous_categorie_dewey' => 'nullable|string',
    'type_livre' => 'nullable|string',
    'langue' => 'nullable|string',
    'description' => 'nullable|string',

    'auteurs' => 'nullable|array',
    'auteurs.*' => 'exists:auteurs,id',
],
[
    'isbn.required' => 'Veuillez saisir un ISBN.',
    'isbn.regex' => 'L’ISBN saisi est invalide. Veuillez entrer exactement 10 chiffres.',
]
);

    // 🔥 Reconstruction ISBN complet
    $data['isbn'] = '978' . $data['isbn'];

    // 🔒 Unicité ISBN
    $exists = Livre::where('isbn', $data['isbn'])
        ->where('id', '!=', $livre->id)
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->withErrors([
                'isbn' => 'Cet ISBN existe déjà.'
            ]);
    }

    /**
     * 🔥 RECALCUL COTE (comme store)
     */
    $numeroLivre = $this->getNextNumeroLivre($data['categorie']);
    $cote = $this->generateCote($data, $numeroLivre);

    // 🔄 UPDATE LIVRE
    $livre->update([
        'titre' => $data['titre'],
        'isbn' => $data['isbn'],
        'categorie' => $data['categorie'] ?? null,
        'sous_categorie_dewey' => $data['sous_categorie_dewey'] ?? null,
        'type_livre' => $data['type_livre'] ?? null,
        'langue' => $data['langue'] ?? null,
        'description' => $data['description'] ?? null,
        'cote' => $cote,
    ]);

    /**
     * 🔥 SYNC EXEMPLAIRES
     * (mise à jour des codes selon nouvelle cote)
     */
    $livre->exemplaires()
        ->orderBy('id')
        ->get()
        ->each(function ($exemplaire, $index) use ($livre) {
            $exemplaire->update([
                'code' => $livre->cote . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT)
            ]);
        });

    // 👥 AUTEURS
    if (!empty($data['auteurs'])) {
        $livre->auteurs()->sync($data['auteurs']);
    }

    return redirect()
        ->route('bibliothecaire.livres.index')
        ->with('success', 'Livre mis à jour avec succès.');
}

public function storeAuteur(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    // 🔎 Vérifie si l'auteur existe déjà (insensible à la casse)
    $exists = Auteur::whereRaw('LOWER(nom) = ?', [strtolower($request->nom)])
        ->first();

    if ($exists) {
        return response()->json([
            'status' => 'exists',
            'message' => 'Cet auteur existe déjà dans votre liste',
            'auteur' => $exists
        ], 200);
    }

    // ➕ Création si inexistant
    $auteur = Auteur::create([
        'nom' => $request->nom
    ]);

    return response()->json([
        'status' => 'created',
        'auteur' => $auteur
    ]);
}

private function getNextNumeroLivre($categorie)
{
    $last = Livre::where('categorie', $categorie)
        ->orderBy('id', 'desc')
        ->first();

    if (!$last) {
        return '001';
    }

    // on récupère le numéro dans la cote (dernier bloc)
    $parts = explode(' ', $last->cote);

    $lastNumero = isset($parts[4]) ? (int)$parts[4] : 0;

    $next = $lastNumero + 1;

    return str_pad($next, 3, '0', STR_PAD_LEFT);
}

private function generateCote($data, $numeroLivre)
{
    $auteurId = str_pad($data['auteurs'][0], 6, '0', STR_PAD_LEFT);

    return implode(' ', [
        $data['sous_categorie_dewey'],
        $auteurId,
        $data['type_livre'],
        $data['langue'],
        $numeroLivre
    ]);
}

private $typeLabels = [
    'ROM' => 'Roman',
    'POE' => 'Poésie',
    'THE' => 'Théâtre',
    'ESS' => 'Essai',
    'PRO' => 'Programmation',
    'WEB' => 'Web Development',
    'DATA' => 'Data Science',
    'DEV' => 'DevOps',
    'SEC' => 'Sécurité',
    'ALG' => 'Algèbre',
    'GEO' => 'Géométrie',
    'CAL' => 'Calcul',
    'STA' => 'Statistiques',
    'TOP' => 'Topologie',
    'PHY' => 'Physique',
    'CHI' => 'Chimie',
    'BIO' => 'Biologie',
    'ECO' => 'Écologie',
    'AST' => 'Astronomie',
    'HIS' => 'Histoire Générale',
    'ANC' => 'Histoire Ancienne',
    'MOY' => 'Moyen Âge',
    'MOD' => 'Histoire Moderne',
    'CON' => 'Histoire Contemporaine'
];

private $langueLabels = [
    'FR' => 'Français',
    'EN' => 'Anglais',
    'ES' => 'Espagnol',
    'DE' => 'Allemand'
];

public function addExemplaire(Livre $livre)
{
    $numero = $livre->exemplaires()->count() + 1;

    Exemplaire::create([
        'livre_id' => $livre->id,
        'code' => $livre->cote . '-' . str_pad($numero, 2, '0', STR_PAD_LEFT),
        'statut' => 'Disponible',
    ]);

    return redirect()
        ->route('bibliothecaire.livres.index')
        ->with('success', 'Exemplaire ajouté avec succès');
}

public function destroyExemplaire($id)
{
    $exemplaire = Exemplaire::findOrFail($id);

    // 🔒 sécurité : éviter suppression si emprunt actif
    if ($exemplaire->statut === 'Emprunté') {
        return back()->withErrors([
            'exemplaire' => 'Impossible de supprimer un exemplaire emprunté.'
        ]);
    }

    $exemplaire->delete();

    return back()->with('success', 'Exemplaire supprimé avec succès.');
}

  public function index(Request $request)
{
    $query = Livre::with('auteurs', 'exemplaires');

    // 🔎 Recherche (titre, ISBN, cote ou auteur)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('titre', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%")
              ->orWhere('cote', 'like', "%{$search}%")
              ->orWhereHas('auteurs', function ($a) use ($search) {
                  $a->where('nom', 'like', "%{$search}%");
              });
        });
    }

    // 🎚️ Filtre catégorie
    if ($request->filled('categorie')) {
        $query->where('categorie', $request->categorie);
    }

    $livres   = $query->latest()->get();
    $auteurs  = Auteur::all();

    // 👇 pour le verifyStudent live dans la modale d'emprunt
    $etudiants = User::where('role', 'Étudiant')
        ->get(['id', 'name', 'matricule']);

    // 🗂️ Catégories existantes (pour le menu déroulant du filtre)
    $categories = Livre::whereNotNull('categorie')
        ->distinct()
        ->orderBy('categorie')
        ->pluck('categorie');

    // 📊 KPI (sur l'ensemble de la collection, indépendamment des filtres)
    $totalLivres       = Livre::count();
    $totalExemplaires  = Exemplaire::count();
    $livresDisponibles = Livre::whereHas('exemplaires', function ($q) {
        $q->where('statut', 'Disponible');
    })->count();
    $livresIndisponibles = $totalLivres - $livresDisponibles;

    return view('bibliothecaire.livres.index', [
        'livres'              => $livres,
        'auteurs'             => $auteurs,
        'etudiants'           => $etudiants,
        'categories'          => $categories,
        'typeLabels'          => $this->typeLabels,
        'langueLabels'        => $this->langueLabels,
        'totalLivres'         => $totalLivres,
        'totalExemplaires'    => $totalExemplaires,
        'livresDisponibles'   => $livresDisponibles,
        'livresIndisponibles' => $livresIndisponibles,
    ]);
}

}