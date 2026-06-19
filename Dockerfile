# ============================================================
#  Étape 1 : compilation des assets front (Vite / Tailwind)
# ============================================================
FROM node:20 AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# ============================================================
#  Étape 2 : application PHP (Laravel)
# ============================================================
FROM php:8.2-cli

# Dépendances système + extensions PHP requises par Laravel
RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath \
    && rm -rf /var/lib/apt/lists/*

# Composer (copié depuis l'image officielle)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Code source de l'application
COPY . .

# Dépendances PHP (mode production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Assets compilés récupérés depuis l'étape 1
COPY --from=assets /app/public/build ./public/build

# Permissions Laravel
RUN chmod -R 775 storage bootstrap/cache

# Script de démarrage (on retire d'éventuels retours chariot Windows)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
