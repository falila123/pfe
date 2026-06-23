#!/bin/sh
set -e

cd /var/www/html

# .env : on part du modèle Docker (valeurs MySQL) — IMPORTANT car
# « php artisan serve » se base sur le fichier .env pour les requêtes web,
# pas sur les variables d'environnement du conteneur.
if [ ! -f .env ]; then
    cp .env.docker .env
fi

# Clé d'application (générée seulement si absente)
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Clé Google Books : injectée depuis l'environnement du conteneur (secret)
if [ -n "$GOOGLE_BOOKS_API_KEY" ]; then
    sed -i "s|^GOOGLE_BOOKS_API_KEY=.*|GOOGLE_BOOKS_API_KEY=${GOOGLE_BOOKS_API_KEY}|" .env
fi

# On évite toute config en cache
php artisan config:clear || true

# Attente que MySQL soit prêt à accepter les connexions
echo "⏳ Attente de la base de données..."
until php -r "new PDO('mysql:host=db;port=3306', 'biblio', 'secret');" >/dev/null 2>&1; do
    sleep 3
done
echo "✅ Base de données prête."

# ============================================================
#  Rôle "scheduler" : on lance uniquement le planificateur Laravel
#  (schedule:work exécute les tâches planifiées chaque minute :
#   expiration des réservations 24h + rappels email J-1)
# ============================================================
if [ "$CONTAINER_ROLE" = "scheduler" ]; then
    echo "🕒 Démarrage du planificateur (schedule:work)..."
    exec php artisan schedule:work
fi

# ============================================================
#  Rôle "app" (par défaut) : migrations + seed + serveur web
# ============================================================
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true

echo "🚀 Démarrage du serveur Laravel..."
exec php artisan serve --host=0.0.0.0 --port=8000
