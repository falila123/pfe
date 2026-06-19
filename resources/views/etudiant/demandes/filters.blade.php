<form method="GET"
      action="{{ route('etudiant.demandes.index') }}"
      class="d-flex align-items-center gap-2 mb-4">

    <select name="statut" class="form-select" onchange="this.form.submit()" style="flex: 0 0 240px;">
        <option value="">Tous les statuts</option>
        @foreach(['En attente' => 'En attente', 'Acceptée' => 'Réservé', 'Récupérée' => 'Récupérée', 'Expirée' => 'Expirée', 'Refusée' => 'Refusée'] as $value => $label)
            <option value="{{ $value }}" @selected($statut === $value)>{{ $label }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-filter me-1"></i> Filtrer
    </button>

    <a href="{{ route('etudiant.demandes.index') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

</form>
