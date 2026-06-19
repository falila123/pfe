<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Changer mon mot de passe - Bibliothèque universitaire</title>

<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    background: linear-gradient(135deg, #eef2ff 0%, #f6f8fc 60%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    padding: 20px;
}

.pwd-card {
    width: 100%;
    max-width: 460px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    padding: 40px 35px;
}

.pwd-icon {
    width: 64px; height: 64px;
    margin: 0 auto 18px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #fff; font-size: 1.6rem;
}
.pwd-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 6px; }
.pwd-subtitle { color: #64748b; text-align: center; margin-bottom: 24px; font-size: 0.9rem; }

.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 0.88rem; }
.form-group .input-wrap { position: relative; }
.form-group .input-wrap i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 0.9rem;
}
.form-group input[type="password"] {
    width: 100%; padding: 10px 12px 10px 36px;
    border: 2px solid #e2e8f0; border-radius: 8px;
    font-size: 0.92rem; transition: all 0.25s ease;
}
.form-group input:focus {
    outline: none; border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
}

.btn-pwd {
    width: 100%; padding: 11px; border: none; border-radius: 8px;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #fff; font-weight: 700; font-size: 0.95rem; cursor: pointer;
    transition: all 0.25s ease;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-pwd:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(79,70,229,0.3); }

.alert-error {
    background: #fde8e8; color: #b91c1c; border: 1px solid #f5c2c7;
    padding: 10px 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 16px;
}
.pwd-hint { font-size: 0.78rem; color: #94a3b8; margin-top: 4px; }
.logout-row { text-align: center; margin-top: 16px; font-size: 0.85rem; }
.logout-row a { color: #64748b; text-decoration: none; }
.logout-row a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="pwd-card">

    <div class="pwd-icon"><i class="fas fa-key"></i></div>
    <h2 class="pwd-title">Sécurisez votre compte</h2>
    <p class="pwd-subtitle">
        Pour votre première connexion, vous devez définir un nouveau mot de passe
        personnel avant d'accéder à la plateforme.
    </p>

    {{-- Erreurs --}}
    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required autofocus autocomplete="new-password">
            </div>
            <div class="pwd-hint">Au moins 8 caractères.</div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="••••••••" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-pwd">
            <i class="fas fa-check"></i> Enregistrer et continuer
        </button>
    </form>

    <div class="logout-row">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="#" onclick="this.closest('form').submit(); return false;">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
            </a>
        </form>
    </div>

</div>

</body>
</html>
