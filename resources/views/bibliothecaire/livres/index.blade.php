@extends('layouts.app-bibliothecaire')

@section('title', 'Gestion des Livres')

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
    window.membresData = @json($membres);
    window.quotasData  = @json($quotas);
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

@include('bibliothecaire.livres.partials.borrow-modal')


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

function escapeHtml(s){
    return (s || '').replace(/[&<>"']/g, c => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[c]));
}

// Libellé d'affichage du rôle (la valeur reste "Prof")
function roleLabel(r){ return r === 'Prof' ? 'Professeur' : r; }

function openBorrowModal(id, code){
    document.getElementById('borrowExemplaireId').value = id;
    document.getElementById('borrowUserId').value = '';
    document.getElementById('borrowExemplaireCode').innerText = code;
    document.getElementById('memberSearch').value = '';
    document.getElementById('memberResults').innerHTML = '';
    document.getElementById('selectedMemberPanel').style.display = 'none';
    document.getElementById('borrowConfirmBtn').disabled = true;

    new bootstrap.Modal(document.getElementById('borrowModal')).show();
}

function filterMembers(){
    const q = document.getElementById('memberSearch').value.trim().toLowerCase();
    const box = document.getElementById('memberResults');

    if(!q){ box.innerHTML = ''; return; }

    const matches = (window.membresData || []).filter(m =>
        (m.name && m.name.toLowerCase().includes(q)) ||
        (m.email && m.email.toLowerCase().includes(q)) ||
        (m.matricule && m.matricule.toLowerCase().includes(q))
    ).slice(0, 8);

    box.innerHTML = matches.length
        ? matches.map(m => `
            <button type="button" class="list-group-item list-group-item-action member-result" data-id="${m.id}">
                <strong>${escapeHtml(m.name)}</strong>
                <span class="badge bg-secondary">${roleLabel(m.role)}</span><br>
                <small class="text-muted">${m.matricule ? 'Matricule : ' + escapeHtml(m.matricule) : escapeHtml(m.email || '')}</small>
            </button>`).join('')
        : `<div class="text-muted small p-2">Aucun membre trouvé.</div>`;
}

function selectBorrowMember(m){
    document.getElementById('borrowUserId').value = m.id;
    document.getElementById('memberResults').innerHTML = '';
    document.getElementById('memberSearch').value = m.name;

    const quota     = (window.quotasData || {})[m.role] || null;
    const maxLivres = quota ? quota.max_livres : null;
    const maxJours  = quota ? quota.max_jours  : null;
    // livres occupés = emprunts en cours + réservations en attente de retrait
    const actifs    = (m.emprunts_actifs ?? 0) + (m.reservations ?? 0);

    document.getElementById('selMemberName').innerText = m.name + ' ';
    document.getElementById('selMemberType').innerText = roleLabel(m.role);
    document.getElementById('selMemberId').innerText =
        m.matricule ? ('Matricule : ' + m.matricule)
        : (m.numero_piece ? ('Pièce : ' + m.numero_piece) : (m.email || ''));

    document.getElementById('selectedMemberPanel').style.display = 'block';

    const warn = document.getElementById('quotaWarning');
    const confirmBtn = document.getElementById('borrowConfirmBtn');
    const datesRow = document.getElementById('borrowDatesRow');

    // ⛔ Limite atteinte → on n'affiche que l'avertissement (pas de durée/dates)
    if(maxLivres !== null && actifs >= maxLivres){
        warn.style.display = 'block';
        warn.innerHTML = `<i class="fas fa-circle-exclamation me-1"></i> Limite d'emprunts atteinte pour cet utilisateur (${actifs}/${maxLivres}).`;
        confirmBtn.disabled = true;
        datesRow.style.display = 'none';
        return;
    }

    // ✅ OK → on affiche durée + dates
    warn.style.display = 'none';
    confirmBtn.disabled = false;
    datesRow.style.display = '';

    const today = new Date();
    document.getElementById('lblDateEmprunt').innerText = today.toLocaleDateString('fr-FR');

    if(maxJours){
        document.getElementById('lblDuree').innerText = `${maxJours} jours`;
        const ret = new Date();
        ret.setDate(ret.getDate() + maxJours);
        document.getElementById('lblDateRetour').innerText = ret.toLocaleDateString('fr-FR');
    } else {
        document.getElementById('lblDuree').innerText = '-';
        document.getElementById('lblDateRetour').innerText = '-';
    }
}

// Sélection d'un membre dans la liste (délégation d'événement)
document.addEventListener('DOMContentLoaded', function () {
    const box = document.getElementById('memberResults');
    if (box) {
        box.addEventListener('click', function (e) {
            const btn = e.target.closest('.member-result');
            if (!btn) return;
            const m = (window.membresData || []).find(x => String(x.id) === btn.dataset.id);
            if (m) selectBorrowMember(m);
        });
    }
});

function returnBook(url){
    const form = document.getElementById('returnForm');
    form.action = url;
    form.submit();
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

<form id="returnForm" method="POST" style="display:none;">
    @csrf
    @method('PATCH')
</form>


@endsection