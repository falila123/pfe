@extends('layouts.app-administration')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-user-circle fa-lg"></i> Bienvenue {{ auth()->user()->name }}</h1>
    <p>Vue d'ensemble de l'activité de la bibliothèque</p>
</div>

{{-- ================= KPI ================= --}}
<div class="row g-4">

    <div class="col-md-3">
        <div class="stat-card primary">
            <i class="fas fa-book"></i>
            <div class="stat-number">{{ $totalLivres }}</div>
            <div>Catalogue (livres)</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <i class="fas fa-users"></i>
            <div class="stat-number">{{ $usersActifs }}</div>
            <div>Utilisateurs actifs</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <i class="fas fa-exchange-alt"></i>
            <div class="stat-number">{{ $empruntsEnCours }}</div>
            <div>Emprunts en cours</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card danger">
            <i class="fas fa-clock"></i>
            <div class="stat-number">{{ $retards }}</div>
            <div>Retards</div>
        </div>
    </div>

</div>

{{-- ================= TOP CATÉGORIES ================= --}}
<div class="row g-4 mt-1">

    <div class="col-12">
        <div class="table-section">

            <h5 class="mb-3"><i class="fas fa-layer-group"></i> Top catégories</h5>

            @forelse($topCategories as $cat)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="fw-semibold">{{ $cat->categorie }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $cat->total }} livre(s)</span>
                </div>
            @empty
                <p class="text-muted mb-0">Aucune catégorie.</p>
            @endforelse

        </div>
    </div>

</div>

@endsection
