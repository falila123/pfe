@extends('layouts.app-bibliothecaire')

@section('title', 'Dashboard Bibliothécaire')

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
            <div class="stat-number">
                {{ $totalBooks ?? 0 }}
            </div>
            <div>Total Livres</div>
        </div>
    </div>

    <!-- DISPONIBLES -->
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">
                {{ $availableBooks ?? 0 }}
            </div>
            <div>Disponibles</div>
        </div>
    </div>

    <!-- EMPRUNTÉS -->
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="stat-number">
                {{ $borrowedBooks ?? 0 }}
            </div>
            <div>Empruntés</div>
        </div>
    </div>

    <!-- RETARDS -->
    <div class="col-md-3">
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">
                {{ $lateBooks ?? 0 }}
            </div>
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

        <span class="text-muted" style="font-size:0.85rem;">
            Temps réel (backend futur)
        </span>
    </div>

    <div class="card p-3">

        <p class="text-muted mb-2">
            • Dernières activités seront chargées depuis la base de données
        </p>

        <p class="text-muted mb-2">
            • Emprunts récents, retours, retards
        </p>

        <p class="text-muted mb-0">
            • Notifications bibliothécaire
        </p>

    </div>

</div>

@endsection