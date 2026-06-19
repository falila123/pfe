<form method="GET"
      action="{{ route('bibliothecaire.demandes.index') }}"
      class="d-flex align-items-center gap-2 mb-4">

    <select name="statut" class="form-select" style="flex: 0 0 240px;">
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
