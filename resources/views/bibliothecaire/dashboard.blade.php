@extends('layouts.app-bibliothecaire')

@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
    <h1>
        <i class="fas fa-user-circle fa-lg"></i>
        Bienvenue {{ auth()->user()->name }}
    </h1>
    <p>Voici un aperçu de l'activité de la bibliothèque</p>
</div>

<!-- KPI DASHBOARD -->
<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-book"></i></div>
        <div>
            <div class="kpi-val">{{ $totalBooks ?? 0 }}</div>
            <div class="kpi-lab">Total livres</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="kpi-val">{{ $availableBooks ?? 0 }}</div>
            <div class="kpi-lab">Disponibles</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="kpi-val">{{ $borrowedBooks ?? 0 }}</div>
            <div class="kpi-lab">Empruntés</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="kpi-val">{{ $lateBooks ?? 0 }}</div>
            <div class="kpi-lab">Retards</div>
        </div>
    </div>

</div>

<!-- ACTIVITÉ RÉCENTE -->
<div class="table-section mt-4">

    <h5 class="mb-3"><i class="fas fa-bell"></i> Activité récente</h5>

    <div class="activity">

        {{-- DERNIER EMPRUNT --}}
        <div class="activity-item">
            <div class="act-ico primary"><i class="fas fa-book"></i></div>
            <div>
                @if($dernierEmprunt)
                    <div class="act-title">
                        Dernier emprunt : <strong>{{ $dernierEmprunt->livre->titre ?? '—' }}</strong>
                    </div>
                    <div class="act-sub">
                        par {{ $dernierEmprunt->user->name ?? '—' }}
                        · {{ $dernierEmprunt->date_emprunt?->format('d/m/Y') }}
                    </div>
                @else
                    <div class="act-sub">Aucun emprunt en cours.</div>
                @endif
            </div>
        </div>

        {{-- DERNIER RETOUR --}}
        <div class="activity-item">
            <div class="act-ico success"><i class="fas fa-rotate-left"></i></div>
            <div>
                @if($dernierRetour)
                    <div class="act-title">
                        Dernier retour : <strong>{{ $dernierRetour->livre->titre ?? '—' }}</strong>
                    </div>
                    <div class="act-sub">
                        par {{ $dernierRetour->user->name ?? '—' }}
                        · {{ $dernierRetour->date_retour_effective?->format('d/m/Y') }}
                    </div>
                @else
                    <div class="act-sub">Aucun retour enregistré.</div>
                @endif
            </div>
        </div>

        {{-- DEMANDES EN ATTENTE --}}
        <div class="activity-item">
            <div class="act-ico amber"><i class="fas fa-hourglass-half"></i></div>
            <div>
                @if($demandesEnAttente > 0)
                    <div class="act-title">
                        <strong>{{ $demandesEnAttente }}</strong> demande(s) en attente de validation
                    </div>
                @else
                    <div class="act-sub">Aucune demande en attente.</div>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection
