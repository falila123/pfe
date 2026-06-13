@extends('layouts.app-bibliothecaire')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1>
        <i class="fas fa-user-circle fa-lg"></i>
        Bienvenue {{ auth()->user()->name }}
    </h1>
</div>

<!-- KPI DASHBOARD -->
<div class="row g-4">

    <!-- TOTAL LIVRES -->
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number">{{ $totalBooks ?? 0 }}</div>
            <div>Total Livres</div>
        </div>
    </div>

    <!-- DISPONIBLES -->
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $availableBooks ?? 0 }}</div>
            <div>Disponibles</div>
        </div>
    </div>

    <!-- EMPRUNTÉS -->
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="stat-number">{{ $borrowedBooks ?? 0 }}</div>
            <div>Empruntés</div>
        </div>
    </div>

    <!-- RETARDS -->
    <div class="col-md-3">
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">{{ $lateBooks ?? 0 }}</div>
            <div>Retards</div>
        </div>
    </div>

</div>

<!-- ACTIVITÉ RÉCENTE -->
<div class="alerts-panel mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">
            <i class="fas fa-bell"></i> Activité récente
        </h6>
    </div>

    <div class="card p-3">

        {{-- DERNIER EMPRUNT --}}
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-book text-primary"></i>
            @if($dernierEmprunt)
                <span>
                    Dernier emprunt :
                    <strong>{{ $dernierEmprunt->livre->titre ?? '—' }}</strong>
                    par {{ $dernierEmprunt->user->name ?? '—' }}
                    <small class="text-muted">
                        ({{ $dernierEmprunt->date_emprunt?->format('d/m/Y') }})
                    </small>
                </span>
            @else
                <span class="text-muted">Aucun emprunt en cours.</span>
            @endif
        </div>

        {{-- DERNIER RETOUR --}}
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-undo text-success"></i>
            @if($dernierRetour)
                <span>
                    Dernier retour :
                    <strong>{{ $dernierRetour->livre->titre ?? '—' }}</strong>
                    par {{ $dernierRetour->user->name ?? '—' }}
                    <small class="text-muted">
                        ({{ $dernierRetour->date_retour_effective?->format('d/m/Y') }})
                    </small>
                </span>
            @else
                <span class="text-muted">Aucun retour enregistré.</span>
            @endif
        </div>

        {{-- DEMANDES EN ATTENTE --}}
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-hourglass-half text-warning"></i>
            @if($demandesEnAttente > 0)
                <span>
                    <strong>{{ $demandesEnAttente }}</strong>
                    demande(s) en attente de validation —
                    <a href="{{ route('bibliothecaire.demandes.index') }}">Traiter les demandes</a>
                </span>
            @else
                <span class="text-muted">Aucune demande en attente.</span>
            @endif
        </div>

    </div>

</div>

@endsection
