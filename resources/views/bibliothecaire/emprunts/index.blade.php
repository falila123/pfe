@extends('layouts.app-bibliothecaire')

@section('title', 'Suivi des emprunts')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-history"></i> Suivi des Emprunts</h1>
    <p>Gérez et surveillez les emprunts de livres</p>
</div>

@include('bibliothecaire.emprunts.stats')

@include('bibliothecaire.emprunts.filters')

@include('bibliothecaire.emprunts.table')

@endsection
