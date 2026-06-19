@extends('layouts.app-admin')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-house"></i> Bienvenue {{ Auth::user()->name }}</h1>
    <p>Supervisez l'activité de la plateforme</p>
</div>

{{-- ================= KPI ================= --}}
<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-user-group"></i></div>
        <div>
            <div class="kpi-val">{{ $internes ?? 0 }}</div>
            <div class="kpi-lab">Internes</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-id-card"></i></div>
        <div>
            <div class="kpi-val">{{ $externes ?? 0 }}</div>
            <div class="kpi-lab">Externes</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-user-check"></i></div>
        <div>
            <div class="kpi-val">{{ $activeUsers ?? 0 }}</div>
            <div class="kpi-lab">Utilisateurs actifs</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-user-slash"></i></div>
        <div>
            <div class="kpi-val">{{ $inactiveUsers ?? 0 }}</div>
            <div class="kpi-lab">Utilisateurs inactifs</div>
        </div>
    </div>

</div>

{{-- ================= ACTIVITÉ RÉCENTE ================= --}}
<div class="table-section mt-4">

    <h5 class="mb-3"><i class="fas fa-bell"></i> Activité récente</h5>

    <div class="activity">

        {{-- Nouvel utilisateur --}}
        @if($lastUser)
            <div class="activity-item">
                <div class="act-ico primary"><i class="fas fa-user-plus"></i></div>
                <div>
                    <div class="act-title">Nouvel utilisateur : <strong>{{ $lastUser->name }}</strong></div>
                    <div class="act-sub">{{ $lastUser->role ?? 'Utilisateur' }} · {{ $lastUser->created_at?->format('d/m/Y') }}</div>
                </div>
            </div>
        @endif

        {{-- Dernier membre du personnel ajouté --}}
        @if($lastStaff)
            <div class="activity-item">
                <div class="act-ico success"><i class="fas fa-user-tie"></i></div>
                <div>
                    <div class="act-title">Dernier personnel ajouté : <strong>{{ $lastStaff->name }}</strong></div>
                    <div class="act-sub">{{ $lastStaff->role }} · {{ $lastStaff->created_at?->format('d/m/Y') }}</div>
                </div>
            </div>
        @endif

        {{-- Dernier compte désactivé --}}
        @if($lastDeactivated)
            <div class="activity-item">
                <div class="act-ico danger"><i class="fas fa-user-slash"></i></div>
                <div>
                    <div class="act-title">Dernier compte désactivé : <strong>{{ $lastDeactivated->name }}</strong></div>
                    <div class="act-sub">{{ $lastDeactivated->role }}</div>
                </div>
            </div>
        @endif

        @if(!$lastUser && !$lastStaff && !$lastDeactivated)
            <p class="text-muted mb-0">Aucune activité récente.</p>
        @endif

    </div>

</div>

@endsection