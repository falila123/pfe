
@extends('layouts.app-admin')

@section('title', 'Gestion Utilisateurs')

@section('content')

@if(session('success'))

    <div
        class="alert alert-success fade show auto-dismiss"
        role="alert"
    >

        <i class="fas fa-check-circle me-2"></i>

        {{ session('success') }}

    </div>

@endif

@if(session('message'))

    <div
        class="alert alert-{{ session('status', 'success') }} fade show auto-dismiss"
        role="alert"
    >

        <i class="fas fa-info-circle me-2"></i>

        {{ session('message') }}

    </div>

@endif

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header">

    <h1>
        <i class="fas fa-users-cog"></i>
        Gestion Utilisateurs
    </h1>

    <p>
        Pilotez les comptes des adhérents
    </p>

</div>

{{-- ================= STATS ================= --}}
@include('admin.users.stats')

{{-- ================= FILTERS ================= --}}
@include('admin.users.filters')

{{-- ================= TABLE ================= --}}
@include('admin.users.table')

{{-- ================= CREATE MODAL ================= --}}
@include('admin.users.partials.create-modal')

{{-- ================= EDIT MODAL ================= --}}
@include('admin.users.partials.edit-modal')

@endsection

<script>

    setTimeout(() => {

        document
            .querySelectorAll('.auto-dismiss')
            .forEach(el => {

                el.classList.remove('show');

                setTimeout(() => el.remove(), 300);

            });

    }, 5000);

</script>

