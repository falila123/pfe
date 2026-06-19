<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            overflow-x:hidden;
            background:var(--bg);
            color:var(--ink);
            font-family:'Inter', 'Segoe UI', sans-serif;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position:fixed; top:0; left:0; width:264px; height:100%;
            background:var(--card); border-right:1px solid var(--line);
            padding:22px 16px; display:flex; flex-direction:column; gap:4px;
            z-index:1000; overflow-y:auto;
        }

        .sidebar-brand { display:flex; align-items:center; gap:12px; padding:6px 8px 18px; }
        .sidebar-brand .logo {
            width:42px; height:42px; border-radius:12px; flex-shrink:0;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px;
            box-shadow:0 6px 14px rgba(79,70,229,.35);
        }
        .sidebar-brand b { font-size:1.05rem; font-weight:700; letter-spacing:-.2px; color:var(--ink); display:block; }
        .sidebar-brand small { display:block; color:var(--muted); font-weight:500; font-size:.72rem; }

        .nav-label {
            font-size:.7rem; text-transform:uppercase; letter-spacing:.08em;
            color:#94a3b8; font-weight:600; padding:14px 10px 6px;
        }

        .sidebar a {
            display:flex; align-items:center; gap:12px; padding:11px 12px; border-radius:12px;
            color:#475569; text-decoration:none; font-weight:500; font-size:.92rem; transition:.18s;
        }
        .sidebar a i { width:20px; text-align:center; font-size:1rem; color:#94a3b8; transition:.18s; }
        .sidebar a:hover { background:#f8fafc; color:var(--ink); }
        .sidebar a:hover i { color:var(--ink); }
        .sidebar a.active { background:var(--brand-soft); color:var(--brand); font-weight:600; }
        .sidebar a.active i { color:var(--brand); }

        .side-foot {
            margin-top:auto; display:flex; align-items:center; gap:10px;
            padding:14px 8px 8px; border-top:1px solid var(--line);
        }
        .avatar {
            width:38px; height:38px; border-radius:50%; flex-shrink:0;
            background:var(--brand-soft); color:var(--brand);
            display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem;
        }
        .side-foot .who { font-size:.85rem; font-weight:600; line-height:1.15; color:var(--ink); }
        .side-foot .who small { color:var(--muted); font-weight:500; display:block; }

        .logout-btn {
            width:100%; margin-top:6px; border:1px solid var(--line); color:#475569; background:var(--card);
            padding:11px 12px; border-radius:12px; display:flex; align-items:center; gap:12px;
            cursor:pointer; transition:.18s; font-weight:500; font-size:.92rem; font-family:inherit;
        }
        .logout-btn i { width:20px; text-align:center; color:#94a3b8; }
        .logout-btn:hover { background:#fef2f2; color:var(--red); border-color:#fecaca; }
        .logout-btn:hover i { color:var(--red); }

        /* ===== Main ===== */
        .main { margin-left:264px; padding:26px 34px; max-width:1280px; }

        @media (max-width: 992px) {
            .sidebar { width:100%; height:auto; position:relative; flex-direction:column; }
            .main { margin-left:0; padding:18px 16px; }
        }
    </style>
</head>

<body class="theme-modern">

@php
    $u = auth()->user();
    $parts = preg_split('/\s+/', trim($u->name ?? ''));
    $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
@endphp

<div class="sidebar">

    <div class="sidebar-brand">
        <div class="logo"><i class="fas fa-book-open"></i></div>
        <div>
            <b>Bibliothèque</b>
            <small>Espace {{ $u->role }}</small>
        </div>
    </div>

    {{-- ================= ADMINISTRATION ================= --}}
    @if($u->role === 'Administration')

        <div class="nav-label">Navigation</div>

        <a href="{{ route('administration.dashboard') }}"
           class="{{ request()->routeIs('administration.dashboard') ? 'active' : '' }}">
            <i class="fas fa-grip"></i> Tableau de bord
        </a>

        <a href="{{ route('administration.statistiques.index') }}"
           class="{{ request()->routeIs('administration.statistiques.*') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Statistiques
        </a>

        <div class="nav-label">Compte</div>

        <a href="{{ route('profile.edit') }}"
           class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Mon profil
        </a>

    @endif

    {{-- ================= PIED : utilisateur + déconnexion ================= --}}
    <div class="side-foot">
        <div class="avatar">{{ $initials ?: 'U' }}</div>
        <div class="who">
            {{ $u->name }}
            <small>{{ $u->role }}</small>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn">
            <i class="fas fa-arrow-right-from-bracket"></i> Déconnexion
        </button>
    </form>

</div>

<div class="main">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>