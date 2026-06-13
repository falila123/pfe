<div class="table-section">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Livre</th>
                <th>Auteur(s)</th>
                <th>Date demande</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            @forelse($demandes as $demande)

                <tr>

                    {{-- ÉTUDIANT --}}
                    <td>
                        <span class="student-info">
                            {{ $demande->user->name ?? 'Inconnu' }}
                            <small>({{ $demande->user->matricule ?? '—' }})</small>
                        </span>
                    </td>

                    {{-- LIVRE --}}
                    <td>{{ $demande->livre->titre ?? '—' }}</td>

                    {{-- AUTEUR(S) --}}
                    <td>{{ $demande->livre?->auteurs->pluck('nom')->join(', ') }}</td>

                    {{-- DATE --}}
                    <td>{{ $demande->created_at->format('d/m/Y') }}</td>

                    {{-- STATUT --}}
                    <td>
                        @if($demande->statut === 'En attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($demande->statut === 'Acceptée')
                            <span class="badge bg-success">Acceptée</span>
                        @else
                            <span class="badge bg-danger">Refusée</span>
                            @if($demande->motif_refus)
                                <div class="text-muted small mt-1">
                                    Motif : {{ $demande->motif_refus }}
                                </div>
                            @endif
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td>
                        @if($demande->statut === 'En attente')

                            <div class="d-flex gap-2">
                                <button type="button"
                                        class="btn btn-sm btn-success"
                                        onclick="openValiderModal({{ $demande->id }}, @js($demande->livre->titre), @js($demande->user->name))">
                                    <i class="fas fa-check"></i> Valider
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="openRefuserModal({{ $demande->id }}, @js($demande->livre->titre), @js($demande->user->name))">
                                    <i class="fas fa-times"></i> Refuser
                                </button>
                            </div>

                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Aucune demande trouvée
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>
