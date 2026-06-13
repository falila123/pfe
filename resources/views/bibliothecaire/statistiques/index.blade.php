@extends('layouts.app-bibliothecaire')

@section('title', 'Statistiques')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Statistiques</h1>
    <p>Vue d'ensemble de l'activité de la bibliothèque</p>
</div>

{{-- ================= KPI ================= --}}
<div class="row g-4 mb-4">

    <div class="col">
        <div class="stat-card primary">
            <i class="fas fa-book"></i>
            <div class="stat-number">{{ $totalLivres }}</div>
            <div>Total livres</div>
        </div>
    </div>

    <div class="col">
        <div class="stat-card info">
            <i class="fas fa-copy"></i>
            <div class="stat-number">{{ $totalExemplaires }}</div>
            <div>Total exemplaires</div>
        </div>
    </div>

    <div class="col">
        <div class="stat-card success">
            <i class="fas fa-check-circle"></i>
            <div class="stat-number">{{ $disponibles }}</div>
            <div>Disponibles</div>
        </div>
    </div>

    <div class="col">
        <div class="stat-card primary">
            <i class="fas fa-exchange-alt"></i>
            <div class="stat-number">{{ $empruntsEnCours }}</div>
            <div>Emprunts en cours</div>
        </div>
    </div>

    <div class="col">
        <div class="stat-card danger">
            <i class="fas fa-clock"></i>
            <div class="stat-number">{{ $retards }}</div>
            <div>En retard</div>
        </div>
    </div>

</div>

{{-- ================= GRAPHIQUES ================= --}}
<div class="chart-grid">

    <div class="chart-section">
        <h5><i class="fas fa-chart-line"></i> Emprunts (8 dernières semaines)</h5>
        <div class="chart-container">
            <canvas id="empruntsChart"></canvas>
        </div>
    </div>

    <div class="chart-section">
        <h5><i class="fas fa-chart-pie"></i> Distribution par catégorie</h5>
        <div class="chart-container">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>

</div>

{{-- ================= TABLEAU RÉPARTITION ================= --}}
<div class="table-section">

    <h5 class="mb-3"><i class="fas fa-layer-group"></i> Répartition des ouvrages</h5>

    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Nombre de livres</th>
                <th>Part de la collection</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parCategorie as $cat)
                <tr>
                    <td><strong>{{ $cat->categorie }}</strong></td>
                    <td>{{ $cat->total }}</td>
                    <td>{{ $totalLivres > 0 ? round($cat->total / $totalLivres * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Aucune donnée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- ================= CHART.JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Courbe : emprunts des 8 dernières semaines
new Chart(document.getElementById('empruntsChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Emprunts',
            data: @json($empruntsParSemaine),
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#2563eb',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// Donut : distribution par catégorie
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
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

@endsection
