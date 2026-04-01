FROM php:8.2-cli

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl

# Installer extensions PHP (IMPORTANT !!!)
RUN docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier projet
COPY . .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner permissions
RUN chmod -R 777 storage bootstrap/cache

# Lancer migration + serveur
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
