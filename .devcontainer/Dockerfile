# Dockerfile pour le service 'app'.
FROM    php:8.1-apache

# Variables d'environnement.
ENV     COMPOSER_ALLOW_SUPERUSER=1

# Sélectionne le répertoire de travail.
WORKDIR /var/www/html

# Met à jour les listes du gestionnaire de paquets.
RUN     apt-get update -qq

# Configure Node.js.
# Cette étape supprime l'ancienne installation de Node 12 préinstallée dans l'image et installe Node 18.
RUN     apt-get remove nodejs && \
        rm -rf /usr/local/bin/node* && \
        rm -rf /usr/local/bin/npm* &&  \
        rm -rf /etc/apt/sources.list.d/nodesource.list && \
        curl -s https://deb.nodesource.com/setup_18.x | bash && \
        apt-get install -qy nodejs

# Installe divers dépendances depuis le gestionnaire de paquets :
# - git, zip, un client MariaDB, et sendmail pour la gestion des mails ;
# - les librairies requises pour les dépendances PHP ;
# - Composer installé en global pour la gestion des assets.
RUN     apt-get install -qy \
            git \
            gnupg \
            nodejs \
            unzip \
            zip \
            mariadb-client \
            sendmail \
            libonig-dev \
            libpng-dev \
            libzip-dev && \
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
        apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
        # Cette dernière étape (apt-get clean && rm -rf ...) supprime les fichiers temporaires du gestionnaire de paquets,
        #  pour alléger l'image.

# Configure et installe les extensions PHP requises.
RUN     docker-php-ext-configure zip > /dev/null 2>&1 && \
        docker-php-ext-install -j$(nproc) gd iconv mbstring opcache pdo pdo_mysql zip > /dev/null 2>&1

# Importe la configuration PHP.
COPY    php/php.ini /usr/local/etc/php/conf.d/app.ini

# Importe la configuration Apache et configure la réécriture d'URL.
COPY    apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY    apache/apache.conf /etc/apache2/conf-available/z-app.conf
RUN     a2enmod rewrite remoteip && \
        a2enconf z-app
