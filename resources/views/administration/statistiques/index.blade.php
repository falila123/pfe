@extends('layouts.app-administration')

@section('title', 'Statistiques')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Vue d'ensemble</h1>
    <p>Panorama global de la bibliothèque (fonds & utilisateurs)</p>
</div>

{{-- ================= KPI ================= --}}
<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-user-group"></i></div>
        <div>
            <div class="kpi-val">{{ $adherents }}</div>
            <div class="kpi-lab">Adhérents</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-book"></i></div>
        <div>
            <div class="kpi-val">{{ $totalLivres }}</div>
            <div class="kpi-lab">Catalogue (livres)</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-copy"></i></div>
        <div>
            <div class="kpi-val">{{ $totalExemplaires }}</div>
            <div class="kpi-lab">Exemplaires</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico warning"><i class="fas fa-right-left"></i></div>
        <div>
            <div class="kpi-val">{{ $totalEmprunts }}</div>
            <div class="kpi-lab">Emprunts au total</div>
        </div>
    </div>

</div>

{{-- ================= GRAPHIQUES (panorama) ================= --}}

{{-- Tendance des emprunts (pleine largeur) --}}
<div class="chart-section mb-4">
    <h5><i class="fas fa-chart-line"></i> Emprunts (8 dernières semaines)</h5>
    <div class="chart-container">
        <canvas id="empruntsChart"></canvas>
    </div>
</div>

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

// Donut : catégories
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
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

// Donut : rôles
new Chart(document.getElementById('rolesChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: @json($parRole->pluck('role')),
        datasets: [{
            data: @json($parRole->pluck('total')),
            backgroundColor: ['#4f46e5','#16a34a','#f59e0b','#dc2626','#ec4899'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
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
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
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
