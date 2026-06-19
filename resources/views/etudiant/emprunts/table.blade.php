<div class="table-section">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Livre</th>
                <th>Date emprunt</th>
                <th>Date retour prévue</th>
                <th>Statut</th>
                <th>Indication</th>
            </tr>
        </thead>

        <tbody>

            @forelse($emprunts as $emprunt)

                <tr>
                    <td>{{ $emprunt->livre->titre ?? '—' }}</td>

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
                    <td colspan="5" class="text-center text-muted">
                        Vous n'avez aucun emprunt pour le moment.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>
