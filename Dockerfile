# Dockerfile.apache
FROM php:8.2-apache

# Activer mod_rewrite
RUN a2enmod rewrite

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    libfreetype6-dev libjpeg62-turbo-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath zip xml

# Copier Composer depuis l’image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Node.js (si besoin de Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs

WORKDIR /var/www/html

# Copier l’app
COPY . /var/www/html

# Installer les dépendances PHP & JS
RUN composer install --optimize-autoloader \
  && npm install \
  && chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R 755 storage bootstrap/cache

# Exposer le port Apache
EXPOSE 80

# Lancement par défaut
CMD ["apache2-foreground"]
