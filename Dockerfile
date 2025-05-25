# Dockerfile.apache
FROM php:8.2-apache

# Activer mod_rewrite
RUN a2enmod rewrite

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath zip xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copier les fichiers de configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/production.ini /usr/local/etc/php/conf.d/local.ini

# Copier l'application
COPY . /var/www/html

# Installer les dépendances et optimiser
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exposer le port Apache
EXPOSE 80

# Lancement par défaut
CMD ["apache2-foreground"]
