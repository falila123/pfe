<div class="table-section">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Membre</th>
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

                    {{-- MEMBRE --}}
                    <td>
                        <span class="student-info">
                            {{ $demande->user->name ?? 'Inconnu' }}
                            <small>{{ $demande->user->matricule ?? $demande->user->email }}</small>
                            <span class="badge bg-secondary">{{ $demande->user->role }}</span>
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
                                        class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1"
                                        onclick="openValiderModal({{ $demande->id }}, @js($demande->livre->titre), @js($demande->user->name))">
                                    <i class="fas fa-check"></i>
                                    <span>Valider</span>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                        onclick="openRefuserModal({{ $demande->id }}, @js($demande->livre->titre), @js($demande->user->name))">
                                    <i class="fas fa-times"></i>
                                    <span>Refuser</span>
                                </button>
                            </div>

                        @elseif($demande->statut === 'Acceptée')

                            <form method="POST"
                                  action="{{ route('bibliothecaire.demandes.remettre', $demande) }}"
                                  onsubmit="return confirm('Confirmer la remise du livre à {{ $demande->user->name }} ? Un emprunt sera créé.');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                    <i class="fas fa-hand-holding"></i>
                                    <span>Remis</span>
                                </button>
                            </form>

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
