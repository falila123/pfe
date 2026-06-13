<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - Bibliothèque universitaire</title>

<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px;
}

.login-wrapper {
    display: flex;
    width: 100%;
    max-width: 860px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

/* ===== Panneau gauche ===== */
.login-intro {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #fff;
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    flex: 0.95;
}
.login-intro .intro-icon { font-size: 3.2rem; margin-bottom: 14px; opacity: 0.9; }
.login-intro h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: 8px; }
.login-intro p { font-size: 0.9rem; opacity: 0.9; margin-bottom: 22px; }

.login-features { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.82rem; }
.login-feature { display: flex; align-items: center; gap: 8px; opacity: 0.92; }
.login-feature i { font-size: 1rem; min-width: 18px; }

/* ===== Panneau droit ===== */
.login-form-container {
    padding: 40px 35px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
}
.login-form-title { font-size: 1.6rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
.login-form-subtitle { color: #64748b; margin-bottom: 22px; font-size: 0.9rem; }

.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 0.88rem; }
.form-group .input-wrap { position: relative; }
.form-group .input-wrap i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 0.9rem;
}
.form-group input[type="email"],
.form-group input[type="password"] {
    width: 100%; padding: 10px 12px 10px 36px;
    border: 2px solid #e2e8f0; border-radius: 8px;
    font-size: 0.92rem; transition: all 0.25s ease;
}
.form-group input:focus {
    outline: none; border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

.remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; font-size: 0.86rem; color: #475569; }

.btn-login {
    width: 100%; padding: 11px; border: none; border-radius: 8px;
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #fff; font-weight: 700; font-size: 0.95rem; cursor: pointer;
    transition: all 0.25s ease;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(37,99,235,0.3); }

.forgot-row { text-align: center; margin-top: 14px; font-size: 0.86rem; }
.forgot-row a { color: #2563eb; text-decoration: none; font-weight: 600; }
.forgot-row a:hover { text-decoration: underline; }

.alert-error {
    background: #fde8e8; color: #b91c1c; border: 1px solid #f5c2c7;
    padding: 10px 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 16px;
}
.alert-success {
    background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;
    padding: 10px 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 16px;
}

@media (max-width: 768px) {
    .login-wrapper { flex-direction: column; max-width: 460px; }
    .login-intro { padding: 30px 20px; }
    .login-features { grid-template-columns: 1fr; }
    .login-form-container { padding: 30px 22px; }
}
</style>
</head>
<body>

<div class="login-wrapper">

    {{-- ===== Panneau gauche ===== --}}
    <div class="login-intro">
        <i class="fas fa-book-open intro-icon"></i>
        <h1>Bibliothèque universitaire</h1>
        <p>Système de gestion centralisé</p>

        <div class="login-features">
            <div class="login-feature"><i class="fas fa-book"></i> Gestion des livres</div>
            <div class="login-feature"><i class="fas fa-exchange-alt"></i> Suivi des emprunts</div>
            <div class="login-feature"><i class="fas fa-paper-plane"></i> Demandes en ligne</div>
            <div class="login-feature"><i class="fas fa-chart-bar"></i> Statistiques</div>
        </div>
    </div>

    {{-- ===== Panneau droit ===== --}}
    <div class="login-form-container">
        <h2 class="login-form-title">Connexion</h2>
        <p class="login-form-subtitle">Accédez à votre espace</p>

        {{-- Message de session (ex: lien de réinitialisation envoyé) --}}
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        {{-- Erreurs --}}
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
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>

            <div class="forgot-row">
                <span class="text-muted" style="color:#94a3b8;">
                    Mot de passe oublié ? Contactez l'administration.
                </span>
            </div>
        </form>
    </div>

</div>

</body>
</html>
