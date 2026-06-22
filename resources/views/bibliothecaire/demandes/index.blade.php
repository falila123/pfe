@extends('layouts.app-bibliothecaire')

@section('title', 'Demandes d\'emprunts')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-tasks"></i> Demandes d'emprunts</h1>
    <p>Validez ou refusez les demandes d'emprunt</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@include('bibliothecaire.demandes.stats')

@include('bibliothecaire.demandes.filters')

@include('bibliothecaire.demandes.table')

@include('bibliothecaire.demandes.partials.valider-modal')

@include('bibliothecaire.demandes.partials.refuser-modal')

{{-- ================= MODALE CONFIRMATION RETRAIT ================= --}}
<div class="modal fade" id="retraitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmer le retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Confirmer le retrait de <strong id="retraitLivre"></strong>
                par <strong id="retraitMembre"></strong> ? Un emprunt sera créé.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" id="retraitForm">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">
                        Retirer le livre
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function openRetraitModal(action, membre, livre) {
        document.getElementById('retraitForm').action = action;
        document.getElementById('retraitMembre').innerText = membre;
        document.getElementById('retraitLivre').innerText = livre;
        new bootstrap.Modal(document.getElementById('retraitModal')).show();
    }
</script>

@endsection
