@extends('layouts.app-administration')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-house"></i> Bienvenue {{ auth()->user()->name }}</h1>
    <p>Suivez l'activité de la bibliothèque</p>
</div>

{{-- ================= KPI ================= --}}
<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-exchange-alt"></i></div>
        <div>
            <div class="kpi-val">{{ $empruntsEnCours }}</div>
            <div class="kpi-lab">Emprunts en cours</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-clock"></i></div>
        <div>
            <div class="kpi-val">{{ $retards }}</div>
            <div class="kpi-lab">Retards</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico warning"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="kpi-val">{{ $demandesEnAttente }}</div>
            <div class="kpi-lab">Demandes en attente</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="kpi-val">{{ $tauxDispo }}%</div>
            <div class="kpi-lab">Taux de disponibilité</div>
        </div>
    </div>

</div>

{{-- ================= TOP CATÉGORIES ================= --}}
<div class="row g-4 mt-1">

    <div class="col-12">
        <div class="table-section">

            <h5 class="mb-3"><i class="fas fa-layer-group"></i> Top catégories</h5>

            @php $variants = ['primary', 'info', 'success', 'warning', 'danger']; @endphp
            <div class="activity">
                @forelse($topCategories as $cat)
                    <div class="activity-item">
                        <div class="act-ico {{ $variants[$loop->index % count($variants)] }}">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                            <span class="act-title fw-semibold">{{ $cat->categorie }}</span>
                            <span class="act-sub">{{ $cat->total }} livre(s)</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucune catégorie.</p>
                @endforelse
            </div>

        </div>
    </div>

</div>

@endsection
