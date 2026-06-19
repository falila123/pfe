@extends('layouts.app-etudiant')

@section('title', 'Notifications')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-bell"></i> Notifications</h1>
    <p>Consultez les mises à jour de vos demandes</p>
</div>

{{-- ================= FILTRE ================= --}}
<form method="GET"
      action="{{ route('etudiant.notifications.index') }}"
      class="d-flex align-items-center gap-2 mb-4">

    <select name="statut" class="form-select" onchange="this.form.submit()" style="flex: 0 0 240px;">
        <option value="">Toutes les notifications</option>
        @foreach(['Acceptée', 'Récupérée', 'Refusée', 'Expirée'] as $s)
            <option value="{{ $s }}" @selected($statut === $s)>{{ $s }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-filter me-1"></i> Filtrer
    </button>

    <a href="{{ route('etudiant.notifications.index') }}" class="btn btn-primary">
        <i class="fas fa-rotate me-1"></i> Réinitialiser
    </a>

</form>

<div class="table-section">

    @forelse($notifications as $notif)

        @php
            $st = $notif->data['statut'] ?? '';
            $icon = match($st) {
                'Acceptée', 'Récupérée' => 'fa-check-circle text-success',
                'Expirée'               => 'fa-clock text-warning',
                'Refusée'               => 'fa-times-circle text-danger',
                default                 => 'fa-bell text-secondary',
            };
        @endphp

        <div class="d-flex justify-content-between align-items-start py-3 border-bottom">

            <div class="d-flex gap-3">

                <i class="fas fa-lg mt-1 {{ $icon }}"></i>

                <div>
                    <div class="{{ is_null($notif->read_at) ? 'fw-bold' : '' }}">
                        {{ $notif->data['message'] ?? '' }}
                    </div>

                    @if($st === 'Refusée' && !empty($notif->data['motif']))
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

        <p class="text-muted mb-0">
            {{ $statut ? 'Aucune notification « ' . $statut .' ».' : 'Aucune notification pour le moment.' }}
        </p>

    @endforelse

</div>

{{-- ================= PAGINATION ================= --}}
@if($notifications->hasPages())
    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
@endif

@endsection
