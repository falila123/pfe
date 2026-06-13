<form method="GET"
      action="{{ route('bibliothecaire.emprunts.suivi') }}"
      class="search-add-card d-flex align-items-center gap-2 mb-4 p-3">

    <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control"
           placeholder="🔎 Rechercher un étudiant ou un livre...">

    <select name="statut" class="form-select">
        <option value="">Tous les statuts</option>
        <option value="En cours"  @selected(request('statut') === 'En cours')>En cours</option>
        <option value="En retard" @selected(request('statut') === 'En retard')>En retard</option>
        <option value="Retourné"  @selected(request('statut') === 'Retourné')>Retournés</option>
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-search me-1"></i> Rechercher
    </button>

    <a href="{{ route('bibliothecaire.emprunts.suivi') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

</form>
