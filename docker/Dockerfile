# Dockerfile pour le service 'app'.
FROM    php:8.1-apache

# Variables d'environnement.
ENV     COMPOSER_ALLOW_SUPERUSER=1

# Expose le port 80 du conteneur à l'hôte et sélectionne le répertoire de travail.
EXPOSE  80
WORKDIR /var/www/html

# Installe git, zip, mariadb, sendmail, les librairies requises pour les dépendances PHP, et Node/npm.
# Composer est également installé en global.
RUN     apt-get update -qq && \
        apt-get install -qy \
        git \
        gnupg \
        unzip \
        zip \
        mariadb-client \
        sendmail \
        libonig-dev \
        libpng-dev \
        libzip-dev \
        nodejs \
        npm && \
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
        apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
# Cette dernière étape (apt-get clean && rm -rf ...) supprime les fichiers temporaire d'apt, pour alléger l'image.

# Configure et installe les extensions PHP requises.
RUN     docker-php-ext-configure zip > /dev/null && \
        docker-php-ext-install -j$(nproc) gd iconv mbstring opcache pdo pdo_mysql zip > /dev/null

# Importe la configuration PHP.
COPY    docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Importe la configuration Apache et configure la réécriture d'URL.
COPY    docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY    docker/apache/apache.conf /etc/apache2/conf-available/z-app.conf
RUN     a2enmod rewrite remoteip && \
        a2enconf z-app
