@extends('layouts.app-etudiant')

@section('title', 'Mes demandes')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-paper-plane"></i> Mes demandes</h1>
    <p>Suivez l'état de vos demandes d'emprunt</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@include('etudiant.demandes.filters')

@include('etudiant.demandes.table')

@if($demandes->hasPages())
    <div class="mt-3">
        {{ $demandes->links() }}
    </div>
@endif

{{-- ================= MODALE CONFIRMATION ANNULATION ================= --}}
<div class="modal fade" id="annulerDemandeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-triangle-exclamation text-danger me-2"></i>Annuler la demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Êtes-vous sûr de vouloir annuler votre demande ? Cette action est définitive.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Non, garder
                </button>
                <form method="POST" id="annulerDemandeForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Oui, annuler
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function confirmAnnulerDemande(action) {
        document.getElementById('annulerDemandeForm').action = action;
        new bootstrap.Modal(document.getElementById('annulerDemandeModal')).show();
    }
</script>

@endsection
