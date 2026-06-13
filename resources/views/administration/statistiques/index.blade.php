@extends('layouts.app-administration')

@section('title', 'Statistiques')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Vue d'ensemble</h1>
    <p>Panorama global de la bibliothèque (fonds & utilisateurs)</p>
</div>

{{-- ================= KPI ================= --}}
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stat-card primary">
            <i class="fas fa-book"></i>
            <div class="stat-number">{{ $totalLivres }}</div>
            <div>Total livres</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <i class="fas fa-copy"></i>
            <div class="stat-number">{{ $totalExemplaires }}</div>
            <div>Total exemplaires</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <i class="fas fa-user-check"></i>
            <div class="stat-number">{{ $usersActifs }}</div>
            <div>Utilisateurs actifs</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card primary">
            <i class="fas fa-exchange-alt"></i>
            <div class="stat-number">{{ $empruntsEnCours }}</div>
            <div>Emprunts en cours</div>
        </div>
    </div>

</div>

{{-- ================= GRAPHIQUES (panorama) ================= --}}
<div class="chart-grid">

    <div class="chart-section">
        <h5><i class="fas fa-chart-pie"></i> Répartition par catégorie</h5>
        <div class="chart-container">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>

    <div class="chart-section">
        <h5><i class="fas fa-users"></i> Répartition des utilisateurs par rôle</h5>
        <div class="chart-container">
            <canvas id="rolesChart"></canvas>
        </div>
    </div>

</div>

{{-- ================= CHART.JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Donut : catégories
new Chart(document.getElementById('categoriesChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: @json($parCategorie->pluck('categorie')),
        datasets: [{
            data: @json($parCategorie->pluck('total')),
            backgroundColor: ['#2563eb','#16a34a','#f59e0b','#dc2626','#ec4899','#8b5cf6','#06b6d4'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

// Donut : rôles
new Chart(document.getElementById('rolesChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: @json($parRole->pluck('role')),
        datasets: [{
            data: @json($parRole->pluck('total')),
            backgroundColor: ['#2563eb','#16a34a','#f59e0b','#dc2626','#ec4899'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});
</script>

@endsection
