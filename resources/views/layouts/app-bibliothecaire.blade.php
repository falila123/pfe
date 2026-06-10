<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bibliothèque - Bibliothécaire')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/livres.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background: #2563eb;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }

        .sidebar h4 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            gap: 8px;
            border-radius: 8px;
            margin: 0 10px;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }

        .logout-btn {
            width: 90%;
            margin-left: 10px;
            border: 1px solid rgba(255,255,255,0.5);
            color: white;
            background: transparent;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .logout-btn:hover {
            background: white;
            color: #2563eb;
        }

        .main {
            padding-left: 240px;
            padding-right: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                overflow-x: auto;
            }

            .main {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">

    <h4><i class="fas fa-book-open"></i> Biblio</h4>

    {{-- ================= BIBLIOTHECAIRE ================= --}}
     @if(auth()->user()->role === 'Bibliothécaire')

    <a href="{{ route('bibliothecaire.dashboard') }}"
       class="{{ request()->routeIs('bibliothecaire.dashboard') ? 'active' : '' }}">

        <i class="fas fa-home"></i> Accueil
    </a>

    <a href="{{ route('bibliothecaire.livres.index') }}"
   class="{{ request()->routeIs('bibliothecaire.livres.*') ? 'active' : '' }}">
    <i class="fas fa-book"></i> Gestion des livres
</a>

    <a href="#">
        <i class="fas fa-tasks"></i> Demandes d’emprunts
    </a>

    <a href="#">
        <i class="fas fa-history"></i> Suivi des emprunts
    </a>

    <a href="#">
        <i class="fas fa-chart-bar"></i> Statistiques
    </a>

@endif

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}" style="margin-top:auto;">
        @csrf
        <button class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </button>
    </form>

</div>

<div class="main">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="appToast" class="toast align-items-center text-white bg-primary border-0" role="alert">
        
        <div class="d-flex">
            
            <div class="toast-body" id="toastMessage">
                Message
            </div>

        </div>

    </div>
</div>

</body>
</html>