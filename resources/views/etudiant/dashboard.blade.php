@extends('layouts.app-etudiant')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-house"></i> Bienvenue {{ auth()->user()->name }}</h1>
    <p>Votre bibliothèque, à portée de main.</p>
</div>

{{-- ================= KPI ================= --}}
<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="kpi-val">{{ $demandesEnAttente }}</div>
            <div class="kpi-lab">Demandes en attente</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="kpi-val">{{ $empruntsEnCours }}</div>
            <div class="kpi-lab">Emprunts en cours</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="kpi-val">{{ $empruntsRetard }}</div>
            <div class="kpi-lab">En retard</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-check"></i></div>
        <div>
            <div class="kpi-val">{{ $retournes }}</div>
            <div class="kpi-lab">Retournés</div>
        </div>
    </div>

</div>

{{-- ================= RÉSUMÉ ================= --}}
<div class="row g-4">

    {{-- À RENDRE PROCHAINEMENT --}}
    <div class="col-md-6">
        <div class="table-section">

            <h5 class="mb-3">
                <i class="fas fa-clock"></i> À rendre prochainement
            </h5>

            @forelse($aRendre as $emprunt)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $emprunt->livre->titre ?? '—' }}</div>
                        <small class="text-muted">
                            Retour prévu : {{ $emprunt->date_retour_prevue?->format('d/m/Y') }}
                        </small>
                    </div>
                    <span class="badge {{ $emprunt->indication_classe }}">
                        {{ $emprunt->indication }}
                    </span>
                </div>
            @empty
                <p class="text-muted mb-0">Aucun livre à rendre.</p>
            @endforelse

        </div>
    </div>

    {{-- DERNIÈRE DEMANDE --}}
    <div class="col-md-6">
        <div class="table-section">

            <h5 class="mb-3">
                <i class="fas fa-paper-plane"></i> Dernière demande
            </h5>

            @if($derniereDemande)
                <div class="d-flex justify-content-between align-items-center py-2">
                    <div>
                        <div class="fw-semibold">{{ $derniereDemande->livre->titre ?? '—' }}</div>
                        <small class="text-muted">{{ $derniereDemande->created_at->format('d/m/Y') }}</small>
                    </div>
                    @if($derniereDemande->statut === 'En attente')
                        <span class="badge bg-warning text-dark">En attente</span>
                    @elseif($derniereDemande->statut === 'Acceptée')
                        <span class="badge bg-success">Acceptée</span>
                    @else
                        <span class="badge bg-danger">Refusée</span>
                    @endif
                </div>
            @else
                <p class="text-muted mb-0">Aucune demande effectuée.</p>
            @endif

        </div>
    </div>

</div>

@endsection
