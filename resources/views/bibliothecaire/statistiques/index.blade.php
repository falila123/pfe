@extends('layouts.app-bibliothecaire')

@section('title', 'Statistiques')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Statistiques</h1>
    <p>Vue d'ensemble de l'activité de la bibliothèque</p>
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

    <div class="chart-section">
        <h5><i class="fas fa-venus-mars"></i> Emprunts par sexe</h5>
        <div class="chart-container">
            <canvas id="sexeChart"></canvas>
        </div>
    </div>

    <div class="chart-section">
        <h5><i class="fas fa-building-user"></i> Emprunts : internes vs externes</h5>
        <div class="chart-container">
            <canvas id="interneExterneChart"></canvas>
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
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79,70,229,0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#4f46e5',
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
            backgroundColor: ['#4f46e5','#16a34a','#f59e0b','#dc2626','#ec4899','#8b5cf6','#06b6d4'],
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

// Donut : emprunts par sexe
new Chart(document.getElementById('sexeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: @json($sexeLabels),
        datasets: [{
            data: @json($sexeValues),
            backgroundColor: ['#4f46e5', '#ec4899', '#94a3b8'],
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

// Barres : internes vs externes
new Chart(document.getElementById('interneExterneChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Internes', 'Externes'],
        datasets: [{
            label: 'Emprunts',
            data: [{{ $empruntsInternes }}, {{ $empruntsExternes }}],
            backgroundColor: ['#16a34a', '#0ea5e9'],
            borderRadius: 8,
            barThickness: 70
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>

@endsection
