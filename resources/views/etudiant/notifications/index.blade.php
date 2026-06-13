@extends('layouts.app-etudiant')

@section('title', 'Notifications')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-bell"></i> Notifications</h1>
    <p>Le suivi de vos demandes traitées</p>
</div>

<div class="table-section">

    @forelse($notifications as $notif)

        <div class="d-flex justify-content-between align-items-start py-3 border-bottom">

            <div class="d-flex gap-3">

                <i class="fas fa-lg mt-1
                    {{ ($notif->data['statut'] ?? '') === 'Acceptée'
                        ? 'fa-check-circle text-success'
                        : 'fa-times-circle text-danger' }}"></i>

                <div>
                    <div class="{{ is_null($notif->read_at) ? 'fw-bold' : '' }}">
                        {{ $notif->data['message'] ?? '' }}
                    </div>

                    @if(($notif->data['statut'] ?? '') === 'Refusée' && !empty($notif->data['motif']))
                        <small class="text-muted">Motif : {{ $notif->data['motif'] }}</small><br>
                    @endif

                    <small class="text-muted">
                        {{ $notif->created_at->format('d/m/Y à H:i') }}
                    </small>
                </div>

            </div>

            @if(is_null($notif->read_at))
                <span class="badge bg-primary">Nouveau</span>
            @endif

        </div>

    @empty

        <p class="text-muted mb-0">Aucune notification pour le moment.</p>

    @endforelse

</div>

@endsection
