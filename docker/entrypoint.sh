#!/bin/sh
set -e

cd /var/www/html

# .env : on part de l'exemple s'il n'existe pas dans le conteneur
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Clé d'application (générée seulement si absente)
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# On évite toute config en cache (les variables d'environnement priment)
php artisan config:clear || true

# Attente que MySQL soit prêt à accepter les connexions
echo "⏳ Attente de la base de données..."
until php -r "new PDO('mysql:host=db;port=3306', 'biblio', 'secret');" >/dev/null 2>&1; do
    sleep 3
done
echo "✅ Base de données prête."

# Migrations + données par défaut (idempotent) + lien de stockage
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true

# Démarrage du serveur Laravel
exec php artisan serve --host=0.0.0.0 --port=8000
