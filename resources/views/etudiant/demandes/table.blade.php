<div class="table-section">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Livre</th>
                <th>Auteur(s)</th>
                <th>Date de la demande</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            @forelse($demandes as $demande)

                <tr>

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
                            <span class="badge bg-success">Réservé</span>
                            @if($demande->date_limite_retrait)
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-clock"></i>
                                    À récupérer avant le {{ $demande->date_limite_retrait->format('d/m/Y à H:i') }}
                                </div>
                            @endif

                        @elseif($demande->statut === 'Récupérée')
                            <span class="badge bg-info text-dark">Récupérée</span>

                        @elseif($demande->statut === 'Expirée')
                            <span class="badge bg-secondary">Expirée</span>
                            <div class="text-muted small mt-1">Livre non récupéré à temps</div>

                        @else
                            <span class="badge bg-danger">Refusée</span>
                            @if($demande->motif_refus)
                                <div class="text-muted small mt-1">
                                    Motif : {{ $demande->motif_refus }}
                                </div>
                            @endif
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td>
                        @if($demande->statut === 'En attente')

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                    onclick="confirmAnnulerDemande('{{ route('etudiant.demandes.destroy', $demande->id) }}')">
                                <i class="fas fa-times"></i>
                                <span>Annuler</span>
                            </button>

                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Vous n'avez aucune demande pour le moment.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>
