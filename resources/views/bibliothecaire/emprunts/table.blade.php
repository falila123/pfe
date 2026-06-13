<div class="table-section">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Livre</th>
                <th>Exemplaire</th>
                <th>Étudiant</th>
                <th>Date emprunt</th>
                <th>Date retour</th>
                <th>Statut</th>
                <th>Indication</th>
            </tr>
        </thead>

        <tbody>

            @forelse($emprunts as $emprunt)

                <tr>
                    <td>{{ $emprunt->livre->titre ?? '—' }}</td>

                    <td>{{ $emprunt->exemplaire->code ?? '—' }}</td>

                    <td>
                        <span class="student-info">
                            {{ $emprunt->user->name ?? 'Inconnu' }}
                            <small>({{ $emprunt->user->matricule ?? '—' }})</small>
                        </span>
                    </td>

                    <td>{{ $emprunt->date_emprunt?->format('d/m/Y') }}</td>

                    <td>{{ $emprunt->date_retour_prevue?->format('d/m/Y') }}</td>

                    <td>
                        <span class="badge {{ $emprunt->statut_classe }}">
                            {{ $emprunt->statut_affiche }}
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $emprunt->indication_classe }}">
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
