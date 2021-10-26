# Dockerfile
FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80
WORKDIR /var/www/html

# Installer git, zip, sendmail, composer, et Node/npm.
RUN apt-get update -qq && \
    apt-get install -qy \
    git \
    gnupg \
    unzip \
    zip \
    mariadb-client \
    sendmail \
    nodejs \
    npm && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
# Cette dernière étape (apt-get clean && rm -rf ...) supprime les fichiers temporaire d'apt, pour alléger l'image.

# Extensions PHP.
RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql mysqli
COPY .docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Apache et réécriture d'URL.
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/apache/apache.conf /etc/apache2/conf-available/z-app.conf
RUN a2enmod rewrite remoteip && \
    a2enconf z-app