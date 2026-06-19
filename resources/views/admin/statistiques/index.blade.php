@extends('layouts.app-admin')

@section('title', 'Statistiques')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Statistiques</h1>
    <p>Vue d'ensemble de l'activité de la bibliothèque</p>
</div>

{{-- ================= GRAPHIQUES ================= --}}
<div class="row g-4">

    {{-- Donut par rôle --}}
    <div class="col-md-6">
        <div class="chart-section">
            <h5><i class="fas fa-chart-pie"></i> Répartition par rôle</h5>
            <div class="chart-container">
                <canvas id="rolesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Donut par sexe --}}
    <div class="col-md-6">
        <div class="chart-section">
            <h5><i class="fas fa-venus-mars"></i> Répartition par sexe</h5>
            <div class="chart-container">
                <canvas id="sexeChart"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ================= TABLEAU PAR RÔLE ================= --}}
<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="table-section">
            <h5 class="mb-3"><i class="fas fa-user-tag"></i> Utilisateurs par rôle</h5>

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Rôle</th>
                        <th>Total</th>
                        <th>Actifs</th>
                        <th>Désactivés</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parRole as $r)
                        <tr>
                            <td><strong>{{ $r->role }}</strong></td>
                            <td>{{ $r->total }}</td>
                            <td><span class="badge bg-success">{{ $r->actifs }}</span></td>
                            <td><span class="badge bg-danger">{{ $r->desactives }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Aucun utilisateur</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= CHART.JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
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
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Donut : répartition par sexe
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
</script>

@endsection
