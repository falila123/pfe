<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Demande;
use App\Models\Exemplaire;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


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
    'couverture_url' => 'nullable|url',
    'nombre_exemplaires' => 'nullable|integer|min:1|max:50',
],
[
    'isbn.required' => 'Veuillez saisir un ISBN.',
    'isbn.regex' => 'L’ISBN saisi est invalide. Veuillez entrer exactement 10 chiffres.',
    'nombre_exemplaires.min' => 'Le nombre d’exemplaires doit être au moins 1.',
    'nombre_exemplaires.max' => 'Le nombre d’exemplaires ne peut pas dépasser 50.',
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
        // Upload manuel prioritaire
        $couverturePath = $request->file('couverture')
            ->store('couvertures', 'public');
    } elseif ($request->filled('couverture_url')) {
        // Sinon, téléchargement automatique depuis l'API (auto-remplissage)
        $couverturePath = $this->telechargerCouverture($request->couverture_url);
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

    // exemplaires (selon le nombre saisi, 1 par défaut)
    $nbExemplaires = (int) ($data['nombre_exemplaires'] ?? 1);

    for ($i = 1; $i <= $nbExemplaires; $i++) {
        Exemplaire::create([
            'livre_id' => $livre->id,
            'code' => $livre->cote . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
            'statut' => 'Disponible',
        ]);
    }

    // auteurs
    $livre->auteurs()->attach($data['auteurs']);

    return redirect()
        ->route('bibliothecaire.livres.index')
        ->with('success', "Livre ajouté avec succès ({$nbExemplaires} exemplaire(s) créé(s)).");
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
    // 🕒 Relibérer les exemplaires des réservations 24h expirées
    Demande::expirerReservationsDepassees();

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

    // 👇 Membres emprunteurs (pour la liste + recherche dans la modale d'emprunt)
    $membres = User::whereIn('role', ['Étudiant', 'Prof', 'Fonctionnaire', 'Externe'])
        ->where('status', 'actif')
        ->withCount([
            'emprunts as emprunts_actifs' => function ($q) {
                $q->where('statut', 'En cours');
            },
            // réservations en attente de retrait (occupent aussi un livre)
            'demandes as reservations' => function ($q) {
                $q->where('statut', 'Acceptée')->whereNotNull('date_limite_retrait');
            },
        ])
        ->orderBy('name')
        ->get(['id', 'name', 'email', 'matricule', 'role', 'telephone', 'numero_piece']);

    // ⚖️ Quotas (durée + nb max de livres) par type, indexés par rôle
    $quotas = \App\Models\Quota::all()->keyBy('role');

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
        'membres'             => $membres,
        'quotas'              => $quotas,
        'categories'          => $categories,
        'typeLabels'          => $this->typeLabels,
        'langueLabels'        => $this->langueLabels,
        'totalLivres'         => $totalLivres,
        'totalExemplaires'    => $totalExemplaires,
        'livresDisponibles'   => $livresDisponibles,
        'livresIndisponibles' => $livresIndisponibles,
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | AUTO-REMPLISSAGE (Google Books + fallback Open Library)
    |--------------------------------------------------------------------------
    */
    public function lookup(Request $request)
    {
        // ISBN nettoyé (chiffres + X), et/ou titre
        $isbn  = preg_replace('/[^0-9Xx]/', '', (string) $request->query('isbn', ''));
        $titre = trim((string) $request->query('titre', ''));

        if (! $isbn && ! $titre) {
            return response()->json(['found' => false], 200);
        }

        $data = null;

        // 1) Google Books par ISBN (le plus précis)
        if ($isbn) {
            $data = $this->fetchGoogleBooks('isbn:' . $isbn);
        }

        // 2) Google Books par titre (recherche plein texte - l'opérateur intitle: est mal encodé)
        if (! $data && $titre) {
            $data = $this->fetchGoogleBooks($titre);
        }

        // 3) Fallback Open Library par ISBN
        if (! $data && $isbn) {
            $data = $this->fetchOpenLibrary($isbn);
        }

        if (! $data) {
            return response()->json(['found' => false], 200);
        }

        // 🔁 Pas de description trouvée ? On la complète via une recherche par titre.
        if (empty($data['description']) && ! empty($data['titre'])) {
            $complement = $this->fetchGoogleBooks($data['titre']);
            if ($complement && ! empty($complement['description'])) {
                $data['description'] = $complement['description'];
            }
        }

        return response()->json(['found' => true] + $data);
    }

    private function fetchGoogleBooks(string $q): ?array
    {
        try {
            $resp = Http::timeout(8)->get('https://www.googleapis.com/books/v1/volumes', [
                'q'          => $q,
                'key'        => config('services.google_books.key'),
                'maxResults' => 5,
                'country'    => 'FR',
            ]);

            if (! $resp->ok()) {
                return null;
            }

            $items = $resp->json('items', []);
            if (empty($items)) {
                return null;
            }

            // On privilégie le 1er résultat qui possède une description
            $info = null;
            foreach ($items as $item) {
                $vi = $item['volumeInfo'] ?? [];
                if (! empty($vi['description'])) {
                    $info = $vi;
                    break;
                }
            }
            if (! $info) {
                $info = $items[0]['volumeInfo'] ?? [];
            }

            return [
                'titre'       => $info['title'] ?? null,
                'auteurs'     => $info['authors'] ?? [],
                'description' => $info['description'] ?? null,
                'couverture'  => $info['imageLinks']['thumbnail']
                              ?? $info['imageLinks']['smallThumbnail']
                              ?? null,
                'langue'      => $info['language'] ?? null,
                'source'      => 'Google Books',
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function telechargerCouverture(string $url): ?string
    {
        try {
            $resp = Http::timeout(10)->get($url);

            if (! $resp->ok() || empty($resp->body())) {
                return null;
            }

            $type = (string) $resp->header('Content-Type');
            $ext  = str_contains($type, 'png')  ? 'png'
                  : (str_contains($type, 'webp') ? 'webp' : 'jpg');

            $path = 'couvertures/' . uniqid('cover_') . '.' . $ext;
            Storage::disk('public')->put($path, $resp->body());

            return $path;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function fetchOpenLibrary(string $isbn): ?array
    {
        try {
            $resp = Http::timeout(8)->get('https://openlibrary.org/api/books', [
                'bibkeys' => 'ISBN:' . $isbn,
                'format'  => 'json',
                'jscmd'   => 'data',
            ]);

            $book = $resp->ok() ? $resp->json('ISBN:' . $isbn) : null;

            if (! $book) {
                return null;
            }

            return [
                'titre'       => $book['title'] ?? null,
                'auteurs'     => collect($book['authors'] ?? [])->pluck('name')->filter()->values()->all(),
                'description' => is_array($book['notes'] ?? null)
                                    ? ($book['notes']['value'] ?? null)
                                    : ($book['notes'] ?? null),
                'couverture'  => $book['cover']['medium'] ?? $book['cover']['large'] ?? null,
                'langue'      => null,
                'source'      => 'Open Library',
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

}