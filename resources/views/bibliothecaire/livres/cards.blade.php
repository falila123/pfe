<div class="book-card">

    {{-- IMAGE COUVERTURE --}}
    @if($livre->couverture)
        <img src="{{ asset('storage/' . $livre->couverture) }}"
             alt="Couverture du livre">
    @else
        <img src="{{ asset('images/default-book.png') }}"
             alt="Couverture par défaut">
    @endif

    <h3 class="book-title">{{ $livre->titre }}</h3>

    <div class="book-author">
        {{ $livre->auteurs->pluck('nom')->join(', ') ?: 'Auteur inconnu' }}
    </div>

    <div class="info-label">
        Catégorie:
        <span class="info-badge">{{ $livre->categorie }}</span>
    </div>

    <div class="info-label">
        Type:
        <span class="info-badge">{{ $typeLabels[$livre->type_livre] ?? $livre->type_livre }}</span>
    </div>

    <div class="info-label">
        Langue:
        <span class="info-badge">{{ $langueLabels[$livre->langue] ?? $livre->langue }}</span>
    </div>

    <div class="info-label">
        Cote:
        <span class="info-badge code-badge">{{ $livre->cote }}</span>
    </div>

    <div class="info-label">
        ISBN:
        <span class="info-badge">{{ $livre->isbn }}</span>
    </div>

    @php
        $total = $livre->exemplaires->count();
        $disponibles = $livre->exemplaires->where('statut', 'Disponible')->count();
    @endphp

    <div class="info-label">
        Exemplaires:
        <span class="info-badge">
            {{ $disponibles }}/{{ $total }}
        </span>
    </div>

  <button class="btn-description"
        onclick="showDescription('{{ $livre->cote }}')">
    📖 Voir description
</button>

    <button class="btn-add-copy"
        onclick="addExemplaire({{ $livre->id }})">
    <i class="fas fa-plus"></i>
    Ajouter exemplaire
</button>

</div>