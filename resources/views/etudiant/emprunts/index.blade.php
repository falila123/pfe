@extends('layouts.app-etudiant')

@section('title', 'Mes emprunts')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-clock"></i> Mes emprunts</h1>
    <p>Restez informé sur l'état de vos emprunts</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@include('etudiant.emprunts.filters')

@include('etudiant.emprunts.table')

@if($emprunts->hasPages())
    <div class="mt-3">
        {{ $emprunts->links() }}
    </div>
@endif

@endsection
