<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bibliothèque - Espace membre')</title>

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
            background:var(--bg);
            color:var(--ink);
            font-family:'Inter', 'Segoe UI', sans-serif;
        }

        /* ===== Top navbar ===== */
        .topnav {
            position:sticky; top:0; z-index:1030;
            background:var(--card); border-bottom:1px solid var(--line);
            box-shadow:0 1px 3px rgba(16,24,40,.05);
        }
        .topnav-inner {
            max-width:1240px; margin:0 auto; padding:0 24px;
            height:66px; display:flex; align-items:center; gap:20px;
        }

        .topnav-brand { display:flex; align-items:center; gap:11px; text-decoration:none; flex-shrink:0; }
        .topnav-brand .logo {
            width:40px; height:40px; border-radius:11px;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px;
            box-shadow:0 6px 14px rgba(79,70,229,.35);
        }
        .topnav-brand b { font-size:1rem; font-weight:700; color:var(--ink); display:block; line-height:1.05; letter-spacing:-.2px; }
        .topnav-brand small { font-size:.7rem; color:var(--muted); font-weight:500; }

        .topnav-links { display:flex; align-items:center; gap:4px; }
        .topnav-links a {
            display:flex; align-items:center; gap:8px; padding:9px 13px; border-radius:10px;
            color:#475569; text-decoration:none; font-weight:500; font-size:.9rem; transition:.18s; white-space:nowrap;
        }
        .topnav-links a i { color:var(--brand); font-size:.95rem; transition:.18s; }
        .topnav-links a:hover { background:var(--brand-soft); color:var(--brand); }
        .topnav-links a.active { background:var(--brand-soft); color:var(--brand); font-weight:600; }

        /* ===== Actions (droite) ===== */
        .topnav-actions { margin-left:auto; display:flex; align-items:center; gap:8px; flex-shrink:0; }

        .topnav-bell {
            position:relative; width:40px; height:40px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            color:var(--brand); text-decoration:none; transition:.18s;
        }
        .topnav-bell:hover, .topnav-bell.active { background:var(--brand-soft); }
        .topnav-bell .nav-count {
            position:absolute; top:4px; right:4px;
            background:var(--red); color:#fff; font-size:.6rem; font-weight:700;
            min-width:16px; height:16px; padding:0 4px; border-radius:20px;
            display:inline-flex; align-items:center; justify-content:center; border:2px solid var(--card);
        }

        .topnav-profile {
            display:flex; align-items:center; gap:9px; text-decoration:none;
            padding:4px 10px 4px 4px; border-radius:10px; transition:.18s;
        }
        .topnav-profile:hover, .topnav-profile.active { background:var(--brand-soft); }
        .avatar {
            width:38px; height:38px; border-radius:50%; flex-shrink:0;
            background:var(--brand-soft); color:var(--brand);
            display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.82rem;
        }
        .topnav-profile:hover .avatar, .topnav-profile.active .avatar { background:var(--brand); color:#fff; }
        .topnav-profile .who { text-align:left; font-size:.82rem; font-weight:600; line-height:1.1; color:var(--ink); }
        .topnav-profile .who small { display:block; color:var(--muted); font-weight:500; }

        .topnav-logout {
            height:40px; padding:0 15px; border-radius:10px; border:1px solid #c7d2fe; background:var(--brand-soft);
            color:var(--brand); display:flex; align-items:center; gap:8px; cursor:pointer; transition:.18s;
            font-weight:600; font-size:.85rem; font-family:inherit; white-space:nowrap;
        }
        .topnav-logout:hover { border-color:var(--brand); box-shadow:0 6px 16px rgba(79,70,229,.22); }

        .topnav-toggle {
            display:none; width:40px; height:40px; border-radius:10px;
            border:1px solid var(--line); background:var(--card); color:#475569; cursor:pointer;
            align-items:center; justify-content:center;
        }

        /* ===== Main ===== */
        .main { max-width:1240px; margin:0 auto; padding:26px 24px; }

        /* ===== Responsive ===== */
        @media (max-width: 980px) {
            .topnav-toggle { display:flex; }
            .topnav-links {
                position:absolute; top:66px; left:0; right:0;
                flex-direction:column; align-items:stretch; gap:2px;
                background:var(--card); border-bottom:1px solid var(--line);
                padding:10px 14px; box-shadow:0 8px 20px rgba(16,24,40,.08);
                display:none;
            }
            .topnav-links.open { display:flex; }
            .topnav-profile .who { display:none; }
            .topnav-logout span { display:none; }
            .topnav-logout { padding:0; width:40px; justify-content:center; }
        }
    </style>
</head>

<body class="theme-modern">

@php
    $u = auth()->user();
    $parts = preg_split('/\s+/', trim($u->name ?? ''));
    $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
    $nbNonLues = $u->unreadNotifications->count();
@endphp

<nav class="topnav">
    <div class="topnav-inner">

        <a href="{{ route('etudiant.dashboard') }}" class="topnav-brand">
            <div class="logo"><i class="fas fa-book-open"></i></div>
            <div>
                <b>Bibliothèque</b>
                <small>Espace membre</small>
            </div>
        </a>

        <button class="topnav-toggle" type="button" onclick="document.getElementById('topnavLinks').classList.toggle('open')">
            <i class="fas fa-bars"></i>
        </button>

        @if(in_array($u->role, ['Étudiant', 'Prof', 'Fonctionnaire', 'Externe']))
        <div class="topnav-links" id="topnavLinks">

            <a href="{{ route('etudiant.dashboard') }}"
               class="{{ request()->routeIs('etudiant.dashboard') ? 'active' : '' }}">
                <i class="fas fa-grip"></i> Tableau de bord
            </a>

            <a href="{{ route('etudiant.catalogue') }}"
               class="{{ request()->routeIs('etudiant.catalogue') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Catalogue
            </a>

            <a href="{{ route('etudiant.demandes.index') }}"
               class="{{ request()->routeIs('etudiant.demandes.index') ? 'active' : '' }}">
                <i class="fas fa-paper-plane"></i> Mes demandes
            </a>

            <a href="{{ route('etudiant.emprunts.index') }}"
               class="{{ request()->routeIs('etudiant.emprunts.index') ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Mes emprunts
            </a>

        </div>
        @endif

        {{-- Actions à droite : cloche + profil + déconnexion --}}
        <div class="topnav-actions">

            <a href="{{ route('etudiant.notifications.index') }}"
               class="topnav-bell {{ request()->routeIs('etudiant.notifications.index') ? 'active' : '' }}"
               title="Notifications">
                <i class="fas fa-bell"></i>
                @if($nbNonLues > 0)
                    <span class="nav-count">{{ $nbNonLues }}</span>
                @endif
            </a>

            <a href="{{ route('profile.edit') }}"
               class="topnav-profile {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
               title="Mon profil">
                <div class="avatar">{{ $initials ?: 'U' }}</div>
                <div class="who">
                    {{ $u->name }}
                    <small>{{ $u->roleLabel() }}</small>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topnav-logout" title="Déconnexion">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    <span>Déconnexion</span>
                </button>
            </form>

        </div>

    </div>
</nav>

<div class="main">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
