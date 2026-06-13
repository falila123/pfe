@extends('layouts.app-admin')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-home"></i> Bienvenue {{ Auth::user()->name }} </h1>
</div>

{{-- ================= KPI ================= --}}
<div class="container-fluid px-0">

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stat-card primary">
                <i class="fas fa-book stat-icon"></i>
                <div class="stat-number">{{ $totalLivres ?? 0 }}</div>
                <div>Livres</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card info">
                <i class="fas fa-copy stat-icon"></i>
                <div class="stat-number">{{ $totalExemplaires ?? 0 }}</div>
                <div>Exemplaires</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card success">
                <i class="fas fa-user-check stat-icon"></i>
                <div class="stat-number">{{ $activeUsers ?? 0 }}</div>
                <div>Utilisateurs actifs</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card danger">
                <i class="fas fa-user-slash stat-icon"></i>
                <div class="stat-number">{{ $inactiveUsers ?? 0 }}</div>
                <div>Utilisateurs inactifs</div>
            </div>
        </div>

    </div>

</div>

{{-- ================= ALERTES ================= --}}
<div class="alerts-panel mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">
            <i class="fas fa-bell"></i> Activité récente
        </h6>

        <span class="text-muted" style="font-size:0.85rem;">
            Temps réel
        </span>
    </div>

    <div class="alerts-container">

        {{-- Dernier emprunt --}}
        @if($lastBorrow)
            <div class="alert-item">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-book text-primary"></i>
                    <span>
                        Livre emprunté :
                        <strong>{{ $lastBorrow->livre->titre ?? 'N/A' }}</strong>
                    </span>
                </div>

                <small class="text-muted">
                    par {{ $lastBorrow->user->name ?? 'N/A' }}
                </small>
            </div>
        @endif

        {{-- Dernier retour --}}
        @if($lastReturn)
            <div class="alert-item">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check text-success"></i>
                    <span>
                        Livre retourné :
                        <strong>{{ $lastReturn->livre->titre ?? 'N/A' }}</strong>
                    </span>
                </div>

                <small class="text-muted">
                    {{ $lastReturn->user->name ?? 'N/A' }}
                </small>
            </div>
        @endif

        {{-- Retards --}}
        @if($lateCount > 0)
            <div class="alert-item">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    <span>Emprunts en retard</span>
                </div>

                <small class="text-muted">
                    {{ $lateCount }} emprunt(s)
                </small>
            </div>
        @endif

        {{-- Dernier utilisateur --}}
        @if($lastUser)
            <div class="alert-item">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-plus text-primary"></i>
                    <span>
                        Nouvel utilisateur :
                        <strong>{{ $lastUser->name }}</strong>
                    </span>
                </div>

                <small class="text-muted">
                    {{ $lastUser->role ?? 'Utilisateur' }}
                </small>
            </div>
        @endif

    </div>

</div>

@endsection