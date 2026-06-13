@php
    $layout = match(auth()->user()->role) {
        'Administrateur' => 'layouts.app-admin',
        'Bibliothécaire' => 'layouts.app-bibliothecaire',
        'Administration' => 'layouts.app-administration',
        default          => 'layouts.app-etudiant',
    };
@endphp

@extends($layout)

@section('title', 'Mon profil')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-user"></i> Mon profil</h1>
    <p>Gérez vos informations et votre mot de passe</p>
</div>

{{-- ALERTES --}}
@if(session('status') === 'profile-updated')
    <div class="alert alert-success">Profil mis à jour ✅</div>
@endif

@if(session('status') === 'password-updated')
    <div class="alert alert-success">Mot de passe mis à jour ✅</div>
@endif

<div class="row g-4">

    {{-- ================= INFORMATIONS ================= --}}
    <div class="col-md-6">
        <div class="table-section">

            <h5 class="mb-3"><i class="fas fa-id-card"></i> Mes informations</h5>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @if(auth()->user()->role === 'Étudiant')
                    <div class="mb-3">
                        <label class="form-label">Matricule</label>
                        <input type="text" class="form-control"
                               value="{{ auth()->user()->matricule }}" disabled>
                        <small class="text-muted">Le matricule est géré par l'administration.</small>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </form>

        </div>
    </div>

    {{-- ================= MOT DE PASSE ================= --}}
    <div class="col-md-6">
        <div class="table-section">

            <h5 class="mb-3"><i class="fas fa-lock"></i> Changer mon mot de passe</h5>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control">
                    @error('current_password', 'updatePassword')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control">
                    @error('password', 'updatePassword')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key me-1"></i> Mettre à jour
                </button>
            </form>

        </div>
    </div>

</div>

@endsection
