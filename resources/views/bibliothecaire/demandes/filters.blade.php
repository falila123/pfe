<form method="GET"
      action="{{ route('bibliothecaire.demandes.index') }}"
      class="search-add-card d-flex align-items-center gap-2 mb-4 p-3">

    <select name="statut" class="form-select" style="max-width:220px;">
        <option value="">Tous les statuts</option>
        <option value="En attente" @selected(request('statut') === 'En attente')>En attente</option>
        <option value="Acceptée"   @selected(request('statut') === 'Acceptée')>Acceptées</option>
        <option value="Refusée"    @selected(request('statut') === 'Refusée')>Refusées</option>
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-filter me-1"></i> Filtrer
    </button>

    <a href="{{ route('bibliothecaire.demandes.index') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

</form>
