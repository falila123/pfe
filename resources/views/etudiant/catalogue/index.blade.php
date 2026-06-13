@extends('layouts.app-etudiant')

@section('title', 'Catalogue des livres')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-book"></i> Catalogue des livres</h1>
    <p>Parcourez la collection et demandez un emprunt</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@include('etudiant.catalogue.filters')

<div class="catalogue-grid">
    @include('etudiant.catalogue.cards')
</div>

@endsection
