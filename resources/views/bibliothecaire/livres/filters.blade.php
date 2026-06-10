<div class="search-add-card d-flex align-items-center gap-2 mb-4 p-3">

    <input type="text"
           id="searchInput"
           class="form-control"
           placeholder="🔎 Rechercher un livre...">

    <select id="categoryFilter" class="form-select">
        <option value="">Toutes les catégories</option>
    </select>

    <button class="btn btn-primary" onclick="filterBooks()">
        <i class="fas fa-search me-1"></i> Rechercher
    </button>

    <button class="btn btn-primary" onclick="resetFilters()">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </button>

    <button class="btn btn-primary" onclick="openAddModal()">
        <i class="fas fa-plus me-1"></i> Ajouter
    </button>

</div>