FROM php:8.3-fpm

# Mettre à jour la liste des paquets et installer les dépendances nécessaires
RUN apt-get update && apt-get upgrade -y

# Installer les dépendances nécessaires pour Laravel et Nginx
RUN apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copier le code de l'application Laravel
WORKDIR /var/www/html
COPY . /var/www/html

# Réparer les permissions
RUN chown -R www-data:www-data /var/www/html

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Exposer le port 9000 pour PHP-FPM
EXPOSE 8080

# Copier la configuration Nginx
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# Démarrer Nginx et PHP-FPM ensemble
CMD service nginx start && php-fpm
