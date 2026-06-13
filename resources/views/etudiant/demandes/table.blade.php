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

                    {{-- ACTION --}}
                    <td>
                        @if($demande->statut === 'En attente')

                            <form method="POST"
                                  action="{{ route('etudiant.demandes.destroy', $demande->id) }}"
                                  onsubmit="return confirm('Annuler cette demande ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                            </form>

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
