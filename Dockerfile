FROM php:8.2-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    libmariadb-dev-compat \
    libmariadb-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application dans le conteneur
COPY . .

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Démarrer le serveur PHP-FPM
CMD ["php-fpm"]
