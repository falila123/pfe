@extends('layouts.app-etudiant')

@section('title', 'Mes emprunts')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-clock"></i> Mes emprunts</h1>
    <p>Vos livres empruntés et leur date de retour</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@include('etudiant.emprunts.stats')

@include('etudiant.emprunts.table')

@endsection
