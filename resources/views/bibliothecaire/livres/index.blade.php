@extends('layouts.app-bibliothecaire')

@section('title', 'Gestion Livres')

@section('content')

{{-- ✅ DÉCLARER livresData EN PREMIER --}}
<script>
    @php
       $livresFormatted = $livres->map(function($livre) {
    return [
        'id' => $livre->id,
        'titre' => $livre->titre,
        'cote' => $livre->cote,
        'description' => $livre->description,
        'isbn' => $livre->isbn,

        'categorie' => $livre->categorie,
        'sous_categorie_dewey' => $livre->sous_categorie_dewey,
        'type_livre' => $livre->type_livre,
        'langue' => $livre->langue,

        'couverture' => $livre->couverture,

        'auteurs' => $livre->auteurs->map(function($auteur) {
            return [
                'id' => $auteur->id,
                'nom' => $auteur->nom
            ];
        })->toArray(),
    ];
});
    @endphp

    // ✅ VARIABLE GLOBALE
    window.livresData = @json($livresFormatted);
    window.auteursData = @json($auteurs);
    console.log("auteursData chargé:", window.auteursData);
    console.log("livresData chargé:", window.livresData);
</script>

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header">

    <h1>
        <i class="fas fa-book"></i>
        Gestion Livres
    </h1>

    <p>
        Gérez votre collection de livres
    </p>

</div>

{{-- ================= ALERTES ================= --}}

@if(session('success'))
    <div id="successAlert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div id="errorAlert" class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

{{-- ================= STATS ================= --}}
@include('bibliothecaire.livres.stats')

{{-- ================= FILTERS ================= --}}
@include('bibliothecaire.livres.filters')

{{-- ================= CARDS ================= --}}
<div id="livresGrid" class="books-grid">

    @foreach($livres as $livre)
        @include('bibliothecaire.livres.cards', ['livre' => $livre])
    @endforeach

</div>

{{-- ================= TABLE ================= --}}
@include('bibliothecaire.livres.table')

{{-- ================= MODALS ================= --}}
@include('bibliothecaire.livres.partials.add-modal')

@include('bibliothecaire.livres.partials.edit-modal')

<div class="modal fade" id="bookDescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye"></i> Détails du livre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <h4 id="descTitle"></h4>
                <p class="text-muted" id="descAuthor"></p>

                <hr>

                <p id="descText" style="white-space: pre-line;"></p>

            </div>

        </div>

    </div>
</div>

<div class="modal fade" id="addExemplaireModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-layer-group"></i> Ajouter un exemplaire</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                Voulez-vous vraiment ajouter un nouvel exemplaire à ce livre ?

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Annuler
                </button>

                <button type="button"
                        class="btn btn-success"
                        id="confirmAddExemplaire">
                    Ajouter
                </button>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="deleteExemplaireModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash"></i>
                    Supprimer un exemplaire
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                Voulez-vous vraiment supprimer cet exemplaire ?
            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Annuler
                </button>

                <button type="button"
                        class="btn btn-danger"
                        id="confirmDeleteExemplaire">
                    Supprimer
                </button>

            </div>

        </div>

    </div>

</div>

<script>
function openAddModal()
{
    const modal = new bootstrap.Modal(
        document.getElementById('addLivreModal')
    );

    modal.show();
}

// ✅ FONCTION showDescription
window.showDescription = function (code) {
    const book = window.livresData.find(b => b.cote === code);

    console.log("CODE REÇU:", code);
    console.log("BOOK TROUVÉ:", book);

    if (!book) {
        alert("Livre non trouvé!");
        return;
    }

    document.getElementById("descTitle").innerText = book.titre;

    document.getElementById("descAuthor").innerText =
        "Auteur : " + (book.auteurs?.map(a => a.nom).join(', ') || 'Inconnu');

    document.getElementById("descText").innerText =
        book.description || "Aucune description disponible.";

    new bootstrap.Modal(
        document.getElementById('bookDescriptionModal')
    ).show();
};

document.addEventListener('DOMContentLoaded', function () {

    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');

    if (successAlert) {
        setTimeout(() => {
            successAlert.remove();
        }, 5000);
    }

    if (errorAlert) {
        setTimeout(() => {
            errorAlert.remove();
        }, 5000);
    }

});

</script>

<form id="addExemplaireForm" method="POST" style="display:none;">
    @csrf
</form>

<form id="deleteExemplaireForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection