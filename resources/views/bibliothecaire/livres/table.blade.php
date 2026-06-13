<h2 class="mt-4">Liste des livres</h2>

<table class="table books-table">

    <thead>
        <tr>
            <th>Cote</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Catégorie</th>
            <th>ISBN</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($livres as $livre)

            @foreach($livre->exemplaires as $exemplaire)

                <tr>

                    {{-- COTE EXEMPLAIRE --}}
                    <td>{{ $exemplaire->code }}</td>

                    {{-- TITRE --}}
                    <td>{{ $livre->titre }}</td>

                    {{-- AUTEURS --}}
                    <td>
                        {{ $livre->auteurs->pluck('nom')->join(', ') }}
                    </td>

                    {{-- CATÉGORIE --}}
                    <td>{{ $livre->categorie }}</td>

                    {{-- ISBN --}}
                    <td>{{ $livre->isbn }}</td>      

                    {{-- STATUT --}}
                    <td>
                        @if($exemplaire->statut === 'Disponible')
                            <span class="success">Disponible</span>
                        @else
                            <span class="danger">Emprunté</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="table-actions">

   <div class="menu-dots" onclick="toggleMenu(this)">

        <i class="fas fa-ellipsis-h"></i>

        <div class="dropdown-menu-book">

            <button class="borrow" type="button"
            onclick="openBorrowModal('{{ $exemplaire->id }}', '{{ $exemplaire->code }}')"
            @disabled($exemplaire->statut === 'Emprunté')>
            <i class="fas fa-book me-2"></i>
            Emprunter
        </button>

        <button class="return" type="button"
            onclick="returnBook('{{ route('bibliothecaire.emprunts.retour', $exemplaire->id) }}')"
            @disabled($exemplaire->statut !== 'Emprunté')>
            <i class="fas fa-undo me-2"></i>
            Retourner
        </button>

       <button class="edit"
        onclick="openEditModal('{{ $livre->cote }}')">
    <i class="fas fa-edit me-2"></i>
    Modifier
</button>

          <button
    type="button"
    class="delete"
    onclick="confirmDeleteExemplaire('{{ route('exemplaires.destroy', $exemplaire->id) }}')">

    <i class="fas fa-trash me-2"></i>
    Supprimer

</button>
</form>

        </div>

    </div>

</td>
                </tr>

            @endforeach

        @empty

            <tr>
               <td colspan="7" class="text-center text-muted">
                    Aucun livre trouvé
                </td>
            </tr>

        @endforelse

    </tbody>

</table>