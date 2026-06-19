@extends('layouts.app-bibliothecaire')

@section('title', 'Demandes d\'emprunts')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-tasks"></i> Demandes d'emprunts</h1>
    <p>Validez ou refusez les demandes d'emprunt</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@include('bibliothecaire.demandes.stats')

@include('bibliothecaire.demandes.filters')

@include('bibliothecaire.demandes.table')

@include('bibliothecaire.demandes.partials.valider-modal')

@include('bibliothecaire.demandes.partials.refuser-modal')

@endsection
