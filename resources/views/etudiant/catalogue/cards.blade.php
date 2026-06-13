@forelse($livres as $livre)

    <div class="catalogue-card">

        <img class="catalogue-cover"
             src="{{ $livre->couverture
                        ? asset('storage/' . $livre->couverture)
                        : 'https://clipart-library.com/images_k/books-clipart-transparent/books-clipart-transparent-10.png' }}"
             alt="Couverture de {{ $livre->titre }}">

        <div class="catalogue-body">

            <div class="catalogue-title">{{ $livre->titre }}</div>

            <div class="catalogue-author">
                <i class="fas fa-user-pen"></i>
                {{ $livre->auteurs->pluck('nom')->join(', ') }}
            </div>

            <span class="catalogue-category">{{ $livre->categorie }}</span>

            @if($livre->nb_disponibles > 0)
                <span class="catalogue-status dispo">Disponible</span>
            @else
                <span class="catalogue-status indispo">Indisponible</span>
            @endif

            <div class="catalogue-action">

                @if(in_array($livre->id, $demandesEnAttente))

                    <button class="catalogue-btn attente" disabled>
                        <i class="fas fa-hourglass-half"></i> Demande en attente
                    </button>

                @elseif($livre->nb_disponibles > 0)

                    <form method="POST" action="{{ route('etudiant.demandes.store') }}">
                        @csrf
                        <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                        <button type="submit" class="catalogue-btn demander">
                            <i class="fas fa-paper-plane"></i> Demander l'emprunt
                        </button>
                    </form>

                @else

                    <button class="catalogue-btn indisponible" disabled>
                        <i class="fas fa-ban"></i> Indisponible
                    </button>

                @endif

            </div>

        </div>

    </div>

@empty

    <p class="text-muted">Aucun livre trouvé.</p>

@endforelse
