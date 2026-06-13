@extends('layouts.app-etudiant')

@section('title', 'Mes demandes')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-paper-plane"></i> Mes demandes</h1>
    <p>Suivez l'état de vos demandes d'emprunt</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@include('etudiant.demandes.stats')

@include('etudiant.demandes.table')

@endsection
