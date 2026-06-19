<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Personnel — Bibliothèque universitaire</title>

<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
  --slate:#4f46e5; --slate-2:#4f46e5; --slate-soft:#eef2ff;
  --ink:#0f172a; --muted:#64748b; --line:#e2e8f0;
}
* { margin:0; padding:0; box-sizing:border-box; }

body {
  background: linear-gradient(135deg, #eef2ff 0%, #f6f8fc 60%);
  min-height: 100vh;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  padding: 20px;
}

.auth-card {
  display: flex;
  width: 100%; max-width: 940px;
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 30px 70px rgba(16,24,40,.18);
  overflow: hidden;
}

/* ===== Panneau gauche (ardoise) ===== */
.auth-illus {
  flex: 1.05;
  background: linear-gradient(160deg, #6366f1 0%, #4f46e5 100%);
  color: #fff;
  padding: 60px 38px;
  display: flex; flex-direction: column; justify-content: flex-start; align-items: center; text-align: center;
}
.auth-illus .big-ico {
  width: 160px; height: 160px; border-radius: 34px;
  background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16);
  display: flex; align-items: center; justify-content: center; font-size: 5rem; margin-bottom: 38px;
}
.auth-feats { display: grid; grid-template-columns: repeat(2, auto); justify-content: center; gap: 16px 30px; }
.auth-feat { display: flex; align-items: center; gap: 10px; font-size: .88rem; color: rgba(255,255,255,.9); white-space: nowrap; }
.auth-feat i { width: 18px; text-align: center; color: #a5b4fc; }

/* ===== Panneau formulaire ===== */
.auth-form { flex: 1; padding: 44px 40px; display: flex; flex-direction: column; justify-content: center; }

.brand { display:flex; align-items:center; gap:12px; margin-bottom: 22px; }
.brand .logo {
  width:44px; height:44px; border-radius:12px;
  background:linear-gradient(135deg,#6366f1,#4f46e5);
  color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px;
  box-shadow:0 6px 14px rgba(79,70,229,.3);
}
.brand b { font-size:1.05rem; font-weight:700; color:var(--ink); display:block; line-height:1.1; }
.brand small { color:var(--muted); font-weight:500; font-size:.74rem; }

.staff-badge {
  display:inline-flex; align-items:center; gap:6px; align-self:flex-start;
  background:var(--slate-soft); color:var(--slate-2);
  font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em;
  padding:4px 10px; border-radius:999px; margin-bottom:12px;
}
.auth-form h1 { font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -.4px; }
.auth-form .sub { color: var(--muted); margin-bottom: 22px; font-size: .9rem; }

.form-group { margin-bottom: 16px; }
.form-group label { display:block; font-weight:600; color:#334155; margin-bottom:6px; font-size:.86rem; }
.input-wrap { position: relative; }
.input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:.9rem; }
.input-wrap input {
  width:100%; padding:11px 13px 11px 38px; border:2px solid var(--line); border-radius:10px;
  font-size:.92rem; font-family:inherit; transition:.2s;
}
.input-wrap input:focus { outline:none; border-color:var(--slate-2); box-shadow:0 0 0 3px rgba(79,70,229,.14); }

.remember-row { display:flex; align-items:center; gap:8px; margin-bottom:18px; font-size:.86rem; color:#475569; }

.btn-login {
  width:100%; padding:12px; border:none; border-radius:10px;
  background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; font-weight:700; font-size:.95rem;
  cursor:pointer; transition:.2s; display:flex; align-items:center; justify-content:center; gap:8px; font-family:inherit;
}
.btn-login:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(79,70,229,.28); }

.forgot-row { text-align:center; margin-top:16px; font-size:.86rem; }
.forgot-row a { color:var(--slate-2); text-decoration:none; font-weight:600; }
.forgot-row a:hover { text-decoration:underline; }

.member-row { text-align:center; margin-top:10px; font-size:.82rem; }
.member-row a { color:var(--muted); text-decoration:none; }
.member-row a:hover { text-decoration:underline; }

.alert-error {
  background:#fee2e2; color:#b91c1c; border:1px solid #fecaca;
  padding:10px 12px; border-radius:10px; font-size:.85rem; margin-bottom:16px;
}

@media (max-width: 820px) {
  .auth-card { flex-direction: column; max-width: 460px; }
  .auth-illus { padding: 32px 26px; }
  .auth-form { padding: 32px 26px; }
}
</style>
</head>
<body>

<div class="auth-card">

    {{-- ===== Panneau gauche ===== --}}
    <div class="auth-illus">
        <div class="big-ico"><i class="fas fa-user-shield"></i></div>

        <div class="auth-feats">
            <div class="auth-feat"><i class="fas fa-users-cog"></i> Gestion des comptes</div>
            <div class="auth-feat"><i class="fas fa-book"></i> Catalogue & exemplaires</div>
            <div class="auth-feat"><i class="fas fa-right-left"></i> Demandes & emprunts</div>
            <div class="auth-feat"><i class="fas fa-chart-pie"></i> Statistiques</div>
        </div>
    </div>

    {{-- ===== Formulaire ===== --}}
    <div class="auth-form">

        <div class="brand">
            <div class="logo"><i class="fas fa-book-open"></i></div>
            <div>
                <b>Bibliothèque</b>
                <small>Espace personnel</small>
            </div>
        </div>

        <span class="staff-badge"><i class="fas fa-lock"></i> Accès réservé</span>

        <h1>Connexion Personnel</h1>
        <p class="sub">Accédez à votre espace de gestion.</p>

        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('personnel.login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="exemple@mail.com" required autofocus autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required autocomplete="current-password">
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin:0;font-weight:500;">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-arrow-right-to-bracket"></i> Se connecter
            </button>

            <div class="forgot-row">
                <a href="{{ route('password.request', ['from' => 'personnel']) }}">
                    <i class="fas fa-unlock-alt"></i> Mot de passe oublié ?
                </a>
            </div>

            <div class="member-row">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i> Vous êtes un membre ? Espace membre
                </a>
            </div>
        </form>
    </div>

</div>

</body>
</html>
