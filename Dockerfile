# Partir d'une image PHP avec Apache
FROM docker.io/library/php:8.1-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Installer les dépendances nécessaires pour pdo_pgsql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

RUN a2enmod rewrite

# Installer l'extension zip, unzip et git
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    git \
    && docker-php-ext-install zip

# Autoriser Composer à s'exécuter en tant que superutilisateur dans Docker
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Modifier le document root de Apache
RUN sed -ri -e 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's|/var/www|/var/www/html/public|g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Installer les dépendances de l'application
RUN composer install --no-interaction

# Autoriser Apache à écrire dans les répertoires var et vendor
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor

