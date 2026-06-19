<form method="GET"
      action="{{ route('bibliothecaire.emprunts.suivi') }}"
      class="d-flex align-items-center gap-2 mb-4">

    <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control"
           style="flex: 0 1 240px;"
           placeholder="🔎 Livre ou membre…">

    <select name="statut" class="form-select" style="flex: 0 0 240px;">
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
