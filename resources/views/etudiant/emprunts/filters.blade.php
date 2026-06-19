<form method="GET"
      action="{{ route('etudiant.emprunts.index') }}"
      class="d-flex align-items-center gap-2 mb-4">

    <select name="statut" class="form-select" onchange="this.form.submit()" style="flex: 0 0 240px;">
        <option value="">Tous les statuts</option>
        @foreach(['En cours', 'En retard', 'Retourné'] as $s)
            <option value="{{ $s }}" @selected($statut === $s)>{{ $s }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-filter me-1"></i> Filtrer
    </button>

    <a href="{{ route('etudiant.emprunts.index') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

</form>
