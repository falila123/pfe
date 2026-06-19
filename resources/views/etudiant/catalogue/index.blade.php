@extends('layouts.app-etudiant')

@section('title', 'Catalogue des livres')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-book"></i> Catalogue des livres</h1>
    <p>Parcourez la collection et demandez un emprunt</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@include('etudiant.catalogue.filters')

<div class="catalogue-grid">
    @include('etudiant.catalogue.cards')
</div>

{{-- ================= MODALE DESCRIPTION ================= --}}
<div class="modal fade" id="bookDescModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-book-open me-2"></i><span id="descTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted mb-3"><i class="fas fa-user-pen me-1"></i><span id="descAuthor"></span></p>
                <p id="descText" style="white-space: pre-line; line-height:1.6;"></p>
            </div>

        </div>
    </div>
</div>

<script>
    function showBookDesc(btn) {
        document.getElementById('descTitle').innerText  = btn.dataset.titre || 'Livre';
        document.getElementById('descAuthor').innerText = btn.dataset.auteur || 'Auteur inconnu';

        const desc = (btn.dataset.desc || '').trim();
        document.getElementById('descText').innerText = desc || 'Aucune description disponible pour ce livre.';

        new bootstrap.Modal(document.getElementById('bookDescModal')).show();
    }
</script>

@endsection
