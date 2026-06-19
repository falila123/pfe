<form method="GET"
      action="{{ route('etudiant.catalogue') }}"
      class="search-add-card d-flex align-items-center gap-2 mb-4 p-3">

    <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control"
           style="flex: 0 1 240px;"
           placeholder="🔎 Titre ou auteur…">

    <select name="categorie" class="form-select" style="flex: 1 1 auto;">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat }}" @selected(request('categorie') === $cat)>
                {{ $cat }}
            </option>
        @endforeach
    </select>

    <select name="disponibilite" class="form-select" style="flex: 1 1 auto;">
        <option value="">Toute disponibilité</option>
        <option value="Disponible" @selected(request('disponibilite') === 'Disponible')>Disponible</option>
        <option value="Indisponible" @selected(request('disponibilite') === 'Indisponible')>Indisponible</option>
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-search me-1"></i> Rechercher
    </button>

    <a href="{{ route('etudiant.catalogue') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

</form>
