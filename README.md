
![Le Monde GC](https://generation-city.com/monde/assets/img/2019/logo-navbar.png)

Le Monde GC constitue l'application Web de référence du [Monde de Génération City](https://generation-city.com/monde/), 
un monde virtuel reposant sur les jeux de construction de ville et le jeu de rôle entre nations.

* Système de cartographie basé sur [OpenLayers 2](https://openlayers.org/two/)
* Gestion économique des pays, impliquant des infrastructures, les quêtes, la cartographie
* Groupes de pays : les organisations
* Interactivité via les communiqués, les réactions, les notifications et le système de vote intégré à l'Assemblée 
générale

Liens : [Site du Monde GC](https://generation-city.com/monde/) -
[Le forum de la communauté](https://www.forum-gc.com/) - [Discord](https://discord.gg/4VMfsaU)

## Table des matières

* [À propos](#à-propos)
* [Structure des dépôts Git](#structure-des-dépôts-git)
* [Installation](#installation)
    * [Prérequis](#prérequis)
    * [Installation via Docker](#installation-via-docker)
        * [Installer Docker](#installer-docker)
        * [Installer l'application](#installer-lapplication)
    * [Lancement et arrêt de l'application](#lancement-et-arrêt-de-lapplication)
* [Site Web et services associés](#site-web-et-services-associés)
  * [PHPMyAdmin](#phpmyadmin)
  * [MailHog](#mailhog)
  * [Gestion des bibliothèques externes](#gestion-des-bibliothèques-externes)
    * [Gérer les dépendances Composer](#gérer-des-dépendances-composer)
    * [Gérer les assets CSS et JavaScript](#gérer-les-assets-css-et-javascript)

## À propos

Le site du Monde GC est une application développée par [Calimero](https://www.forum-gc.com/u167), et lancée en 2013. 
Son développement a été repris par [Sakuro](https://www.forum-gc.com/u615) et [romu23](https://www.forum-gc.com/u81), 
avec la contribution de [Myname](https://www.forum-gc.com/u2345) et de [vallamir](https://www.forum-gc.com/u319). 
L'aspect graphique est réalisé par [Lesime](https://www.forum-gc.com/u23) et romu23.

Le site du Monde GC, depuis la [version 2.5](https://bitbucket.org/Roxayl/mondegc/src/release-2.5/) (juillet 2021), 
repose sur le framework [Laravel](https://laravel.com/), et les nouvelles fonctionnalités du site reposent sur ce 
framework. La [documentation](https://laravel.com/docs/8.x) de Laravel est riche et n'hésitez pas à vous renseigner sur 
son fonctionnement, intuitif et puissant, afin de pouvoir contribuer vous aussi au projet phare de la communauté 
[Génération City](http://www.forum-gc.com/).

## Structure des dépôts Git

Les sources du site sont gérées par Git, hébergées sur un certain nombre de plateformes.

 | Plateforme | Dépôt                                                               | Complet ? | Visibilité | Commentaires                             |
 | ---------- | ------------------------------------------------------------------- | --------- | ---------- | ---------------------------------------- |
 | Bitbucket  | [Roxayl/mondegc](https://bitbucket.org/Roxayl/mondegc/)             | Oui       | Privé      | Dépôt principal                          |
 | GitHub     | [Roxayl/mondegc](https://github.com/Roxayl/mondegc)                 | Oui       | Privé      | Miroir du dépôt principal, lecture seule |
 | GitHub     | [Roxayl/mondegc-laravel](https://github.com/Roxayl/mondegc-laravel) | **Non**   | Public     | Copie limitée du dépôt principal         |

Notez que les sources du dépôt [Roxayl/mondegc-laravel](https://github.com/Roxayl/mondegc-laravel) sont incomplètes
et vous ne pourrez pas installer et exécuter l'application à partir de celui-ci.

## Installation

### Prérequis

Le Monde GC nécessite d'exécuter un environnement de développement comprenant les logiciels suivants :

* **[PHP](https://www.php.net/) 7.4 à 8.0**
* Un moteur de base de données : **[MySQL](https://www.mysql.com/fr/)** ou **[MariaDB](https://mariadb.org/)**
* Un serveur Web : **[Apache](https://httpd.apache.org/)** (fortement conseillé) ou **[nginx](https://www.nginx.com/)**
(nécessite d'adapter les règles de réécriture d'URL)
* **[Composer](https://getcomposer.org/)**, le gestionnaire de dépendances pour PHP
* **[Node.js et npm](https://www.npmjs.com/get-npm)**, un moteur JavaScript et un gestionnaire de dépendances pour des environnements JavaScript

### Installation via Docker

L'application fournit un fichier de configuration permettant d'exécuter l'application de manière conteneurisée, via 
[Docker](https://fr.wikipedia.org/wiki/Docker_(logiciel)). La configuration est décrite dans les fichiers 
[docker-compose.yml](./docker-compose.yml) et [Dockerfile](./Dockerfile).

Ce guide fournit les étapes pour démarrer l'application Web sur votre machine. Elle détaille :

- l'installation de Docker
    - sur Windows
    - sur Ubuntu
- l'installation et l'initialisation de l'application

#### Installer Docker

##### Installer Docker sur Windows

Docker Desktop constitue l'outil idéal pour gérer des applications conteneurisées facilement sur Windows.

L'installation de Docker Desktop est détaillée dans ce [tutoriel](https://geekflare.com/fr/docker-desktop/).

##### Installer Docker sur Linux

La documentation de Docker détaille la procédure pour installer Docker, selon la distribution Linux que vous utilisez. 
Vous devez installer :

- [Docker Engine](https://docs.docker.com/engine/install/), le coeur de Docker ;
- [Docker Compose](https://docs.docker.com/compose/install/), permettant de gérer un ensemble d'applications 
conteneurisées.

#### Installer l'application

Une fois que tout est installé, vous êtes prêt pour déployer l'application Web via Docker.

1. Clonez le dépôt Git dans un répertoire sur votre machine, en ligne de commande :

   ```
    > git clone https://bitbucket.org/Roxayl/mondegc.git
   ```

2. Lancez les conteneurs Docker de l'application via la commande suivante :

   ```
    > docker-compose up -d
   ```

3. Accédez au conteneur de l'application via la commande à saisir dans un terminal, depuis le répertoire où est 
installé l'application.

   ```
    > docker exec -ti mondegc_app sh
   ```

4. Dans le conteneur de l'application, accédez au dossier comprenant les fichiers de l'application et exécutez la
commande permettant d'installer les dépendances et bibliothèques externes PHP (gérée par Composer).

   ```
    > cd /var/www/html
    > composer install
   ```

5. Toujours dans le conteneur de l'application, exécutez la commande d'initialisation. Cette commande va notamment
générer des clés et remplir la base de données. Vous pouvez ensuite sortir du conteneur via la commande ``exit``.

   ```
    > php artisan monde:init
    > exit
   ```

6. Vwalà ! Retrouvez le Monde GC à l'adresse suivante : **[http://localhost](http://localhost)**.

### Lancement et arrêt de l'application

Vous pouvez arrêter l'application via la commande ``docker-compose down``, à partir du répertoire racine. Relancez-la à 
tout moment avec ``docker-compose up -d``.

## Site Web et services associés

Vous pouvez accéder au site via l'adresse : [http://localhost](http://localhost). Par ailleurs, le fichier de 
configuration Docker installe des services annexes permettant de faciliter la gestion des données du site Web, décrits 
ci-dessous.

### PHPMyAdmin

Vous pouvez accéder à une instance de PHPMyAdmin, qui fournit une interface Web pour gérer la base de données, à 
l'adresse : [http://localhost:8080](http://localhost:8080).

### MailHog

MailHog fournit un serveur mail permettant de tester l'envoi de courriers électroniques sortants générés par 
l'application. MailHog est configuré pour fonctionner dès l'initialisation du conteneur Docker. Vous pouvez accéder à 
son interface Web à l'adresse : [http://localhost:8025](http://localhost:8025).

### Gestion des bibliothèques externes

Pour accéder au répertoire de l'application au sein du conteneur principal ``mondegc_app``, vous pouvez taper les 
commandes suivantes dans un terminal dans le répertoire racine :

   ```
    > docker exec -ti mondegc_app sh
    > cd /var/www/html
   ```

À partir de là, vous pouvez accéder à l'interface en ligne de commande fournie par 
[Artisan](https://laravel.com/docs/8.x/artisan), et gérer les dépendances NPM et Composer.

#### Gérer des dépendances Composer

Après avoir effectué l'étape précédente, vous pouvez mettre à jour les bibliothèques externes décrites dans le fichier 
[composer.json](./composer.json) via la commande ``composer update``.

#### Gérer les assets CSS et JavaScript

Toujours dans le conteneur principal, les bibliothèques JavaScript décrits dans le fichier 
[package.json](./package.json) peuvent être installés avec la commande ``npm install``.

Vous pouvez ensuite compiler les assets CSS/SCSS et JavaScript en exécutant ``npm run dev``. Le comportement de la 
compilation des assets est décrit dans le fichier [webpack.mix.js](./webpack.mix.js). Notez que les assets situés
dans le répertoire [assets/](./assets) ne nécessitent pas d'être compilés.
