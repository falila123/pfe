@extends('layouts.app-admin')

@section('title', 'Statistiques')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Statistiques — Utilisateurs</h1>
    <p>Analyse des comptes et des rôles</p>
</div>

{{-- ================= KPI ================= --}}
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stat-card primary">
            <i class="fas fa-users"></i>
            <div class="stat-number">{{ $totalUsers }}</div>
            <div>Total utilisateurs</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <i class="fas fa-user-check"></i>
            <div class="stat-number">{{ $actifs }}</div>
            <div>Actifs</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card danger">
            <i class="fas fa-user-slash"></i>
            <div class="stat-number">{{ $desactives }}</div>
            <div>Désactivés</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <i class="fas fa-user-graduate"></i>
            <div class="stat-number">{{ $etudiants }}</div>
            <div>Étudiants</div>
        </div>
    </div>

</div>

{{-- ================= GRAPHIQUE + TABLEAU ================= --}}
<div class="row g-4">

    {{-- Donut par rôle --}}
    <div class="col-md-5">
        <div class="chart-section">
            <h5><i class="fas fa-chart-pie"></i> Répartition par rôle</h5>
            <div class="chart-container">
                <canvas id="rolesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tableau par rôle --}}
    <div class="col-md-7">
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
            backgroundColor: ['#2563eb','#16a34a','#f59e0b','#dc2626','#ec4899'],
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
