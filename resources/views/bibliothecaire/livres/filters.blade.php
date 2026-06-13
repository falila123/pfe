<form method="GET"
      action="{{ route('bibliothecaire.livres.index') }}"
      class="search-add-card d-flex align-items-center gap-2 mb-4 p-3">

    <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control"
           style="max-width: 220px;"
           placeholder="🔎 Rechercher un livre...">

    <select name="categorie" class="form-select" style="min-width: 240px;">
        <option value="">Toutes les catégories</option>
        @foreach(['Littérature', 'Informatique', 'Mathématiques', 'Science', 'Histoire'] as $cat)
            <option value="{{ $cat }}" @selected(request('categorie') === $cat)>
                {{ $cat }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-search me-1"></i> Rechercher
    </button>

    <a href="{{ route('bibliothecaire.livres.index') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

    <button type="button" class="btn btn-primary" onclick="openAddModal()">
        <i class="fas fa-plus me-1"></i> Ajouter
    </button>

</form>
