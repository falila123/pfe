<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Membre - Bibliothèque universitaire</title>

<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
  --brand:#4f46e5; --brand-soft:#eef2ff; --ink:#0f172a; --muted:#64748b; --line:#e2e8f0;
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

/* ===== Panneau illustration ===== */
.auth-illus {
  flex: 1.05;
  background: linear-gradient(160deg, #eef2ff 0%, #e0e7ff 100%);
  padding: 44px 36px;
  display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
}
.auth-illus img { width: 100%; max-width: 320px; margin-bottom: 26px; }
.auth-illus h2 { font-size: 1.4rem; font-weight: 800; color: var(--ink); letter-spacing: -.4px; margin-bottom: 8px; }
.auth-illus p { color: var(--muted); font-size: .92rem; max-width: 320px; line-height: 1.5; }

/* ===== Panneau formulaire ===== */
.auth-form { flex: 1; padding: 44px 40px; display: flex; flex-direction: column; justify-content: center; }

.brand { display:flex; align-items:center; gap:12px; margin-bottom: 26px; }
.brand .logo {
  width:44px; height:44px; border-radius:12px;
  background:linear-gradient(135deg,#6366f1,#4f46e5);
  color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px;
  box-shadow:0 6px 14px rgba(79,70,229,.35);
}
.brand b { font-size:1.05rem; font-weight:700; color:var(--ink); display:block; line-height:1.1; }
.brand small { color:var(--muted); font-weight:500; font-size:.74rem; }

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
.input-wrap input:focus { outline:none; border-color:var(--brand); box-shadow:0 0 0 3px rgba(79,70,229,.12); }

.remember-row { display:flex; align-items:center; gap:8px; margin-bottom:18px; font-size:.86rem; color:#475569; }

.btn-login {
  width:100%; padding:12px; border:none; border-radius:10px;
  background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; font-weight:700; font-size:.95rem;
  cursor:pointer; transition:.2s; display:flex; align-items:center; justify-content:center; gap:8px; font-family:inherit;
}
.btn-login:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(79,70,229,.32); }

.forgot-row { text-align:center; margin-top:16px; font-size:.86rem; }
.forgot-row a { color:var(--brand); text-decoration:none; font-weight:600; }
.forgot-row a:hover { text-decoration:underline; }

.alert-error {
  background:#fee2e2; color:#b91c1c; border:1px solid #fecaca;
  padding:10px 12px; border-radius:10px; font-size:.85rem; margin-bottom:16px;
}
.alert-success {
  background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;
  padding:10px 12px; border-radius:10px; font-size:.85rem; margin-bottom:16px;
}

@media (max-width: 820px) {
  .auth-card { flex-direction: column; max-width: 460px; }
  .auth-illus { padding: 30px 24px; }
  .auth-illus img { max-width: 200px; margin-bottom: 16px; }
  .auth-form { padding: 32px 26px; }
}
</style>
</head>
<body>

<div class="auth-card">

    {{-- ===== Illustration ===== --}}
    <div class="auth-illus">
        <img src="{{ asset('images/image.png') }}" alt="Lecture à la bibliothèque">
        <h2>Bienvenue à la bibliothèque</h2>
        <p>Consultez, empruntez et suivez vos livres en quelques clics, où que vous soyez.</p>
    </div>

    {{-- ===== Formulaire ===== --}}
    <div class="auth-form">

        <div class="brand">
            <div class="logo"><i class="fas fa-book-open"></i></div>
            <div>
                <b>Bibliothèque</b>
                <small>Espace membre</small>
            </div>
        </div>

        <h1>Connexion</h1>
        <p class="sub">Heureux de vous revoir ! Accédez à votre espace.</p>

        @if (session('status'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
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
                <a href="{{ route('password.request') }}">
                    <i class="fas fa-unlock-alt"></i> Mot de passe oublié ?
                </a>
            </div>
        </form>
    </div>

</div>

</body>
</html>
