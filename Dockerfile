# Utiliser une image PHP avec Nginx
FROM php:8.3-fpm

# Installer les dépendances nécessaires pour Laravel
RUN apt-get update && apt-get install -y \
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

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande par défaut pour démarrer PHP-FPM
CMD ["php-fpm"]

# Copier la configuration Nginx
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# Installer Nginx
RUN apt-get install -y nginx

# Démarrer Nginx et PHP-FPM ensemble
CMD service nginx start && php-fpm
