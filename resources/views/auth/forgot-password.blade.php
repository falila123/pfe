<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mot de passe oublié - Bibliothèque universitaire</title>

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
.form-group input[type="email"] {
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
.alert-success {
    background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;
    padding: 10px 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 16px;
}
.back-row { text-align: center; margin-top: 16px; font-size: 0.86rem; }
.back-row a { color: #4f46e5; text-decoration: none; font-weight: 600; }
.back-row a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="pwd-card">

    <div class="pwd-icon"><i class="fas fa-unlock-alt"></i></div>
    <h2 class="pwd-title">Mot de passe oublié ?</h2>
    <p class="pwd-subtitle">
        Saisissez votre adresse email : nous vous enverrons un lien sécurisé
        pour définir un nouveau mot de passe.
    </p>

    {{-- Message de succès --}}
    @if (session('status'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    {{-- Erreurs --}}
    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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

        <button type="submit" class="btn-pwd">
            <i class="fas fa-paper-plane"></i> Envoyer le lien
        </button>
    </form>

    <div class="back-row">
        <a href="{{ request('from') === 'personnel' ? route('personnel.login') : route('login') }}">
            <i class="fas fa-arrow-left"></i> Retour à la connexion
        </a>
    </div>

</div>

</body>
</html>
