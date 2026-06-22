<div class="table-section">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Livre</th>
                <th>Membre</th>
                <th>Date demande</th>
                <th>Date emprunt</th>
                <th>Date retour</th>
                <th>Statut</th>
                <th>Indication</th>
            </tr>
        </thead>

        <tbody>

            @forelse($emprunts as $emprunt)

                <tr>
                    <td>{{ $emprunt->livre->titre ?? '-' }}</td>

                    <td>
                        <span class="student-info">
                            {{ $emprunt->user->name ?? 'Inconnu' }}
                            <small>{{ $emprunt->user->matricule ?? $emprunt->user->email }}</small>
                            <span class="badge bg-secondary">{{ $emprunt->user->roleLabel() }}</span>
                        </span>
                    </td>

                    <td>
                        @if($emprunt->demande)
                            {{ $emprunt->demande->created_at->format('d/m/Y') }}
                        @else
                            <span class="text-muted fst-italic">Effectué sur place</span>
                        @endif
                    </td>

                    <td>{{ $emprunt->date_emprunt?->format('d/m/Y') }}</td>

                    <td>{{ $emprunt->date_retour_prevue?->format('d/m/Y') }}</td>

                    <td>
                        <span class="badge {{ $emprunt->statut_classe }}">
                            {{ $emprunt->statut_affiche }}
                        </span>
                    </td>

                    <td>
                        <span class="badge indication-badge {{ $emprunt->indication_classe }}">
                            {{ $emprunt->indication }}
                        </span>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Aucun emprunt trouvé
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>
