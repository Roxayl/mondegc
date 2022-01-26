
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

- [Table des matières](#table-des-matières)
- [À propos](#à-propos)
- [Structure des dépôts Git](#structure-des-dépôts-git)
- [Installation](#installation)
  - [Environnement](#environnement)
  - [Installation via Docker](#installation-via-docker)
    - [Installer Docker](#installer-docker)
    - [Installer l'application](#installer-lapplication)
  - [Lancement et arrêt de l'application](#lancement-et-arrêt-de-lapplication)
- [Développement et tests](#développement-et-tests)
  - [Gestion des bibliothèques externes](#gestion-des-bibliothèques-externes)
    - [Gérer des dépendances Composer](#gérer-des-dépendances-composer)
    - [Gérer les assets CSS et JavaScript](#gérer-les-assets-css-et-javascript)
  - [Tests](#tests)
  - [Helpers](#helpers)
- [Services complémentaires](#services-complémentaires)
  - [PHPMyAdmin](#phpmyadmin)
  - [MailHog](#mailhog)
  - [Documentation de l'API](#documentation-de-lapi)
- [API publique](#api-publique)

## À propos

Le site du Monde GC est une application développée par [Calimero](https://www.forum-gc.com/u167), et lancée en 2013. 
Son développement a été repris par [Sakuro](https://www.forum-gc.com/u615) et [romu23](https://www.forum-gc.com/u81), 
avec la contribution de [Myname](https://www.forum-gc.com/u2345) et de [vallamir](https://www.forum-gc.com/u319). 
L'aspect graphique est réalisé par [Lesime](https://www.forum-gc.com/u23) et romu23.

Le site du Monde GC, depuis la [version 2.5](https://bitbucket.org/Roxayl/mondegc/src/release-2.5/) (juillet 2020), 
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

### Environnement

Le Monde GC s'exécute sur un environnement de développement comprenant les logiciels suivants :

* **[PHP](https://www.php.net/) 7.4 à 8.0**
* Un moteur de base de données : **[MySQL](https://www.mysql.com/fr/)** ou **[MariaDB](https://mariadb.org/)**
* Un serveur Web : **[Apache](https://httpd.apache.org/)** (fortement conseillé) ou **[nginx](https://www.nginx.com/)**
(nécessite d'adapter les règles de réécriture d'URL)
* **[Composer](https://getcomposer.org/)**, le gestionnaire de dépendances pour PHP
* **[Node.js et npm](https://www.npmjs.com/get-npm)**, un moteur JavaScript et un gestionnaire de dépendances pour des 
environnements JavaScript

### Installation via Docker

L'application fournit un fichier de configuration permettant d'exécuter l'application de manière conteneurisée, via 
[Docker](https://fr.wikipedia.org/wiki/Docker_(logiciel)). La configuration est décrite dans les fichiers 
[docker-compose.yml](./docker-compose.yml) et [Dockerfile](./docker/Dockerfile).

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
    > composer install
   ```

5. Toujours dans le conteneur de l'application, exécutez la commande d'initialisation. Cette commande va notamment 
générer des clés et d'autres variables d'environnement.
   ```
    > php artisan monde:init-env
   ```

6. Initialisez ensuite la base de données. Enfin, vous pouvez sortir du conteneur via la commande ``exit``.
   ```
    > php artisan monde:init-db
    > exit
   ```

7. Vwalà ! Retrouvez le Monde GC à l'adresse suivante : **[http://localhost](http://localhost)**. La base de données 
est initialisée avec un utilisateur par défaut, dont vous pouvez utiliser les identifiants pour vous connecter (nom 
d'utilisateur : ``Admin``, mot de passe : ``password``).

### Lancement et arrêt de l'application

Vous pouvez arrêter l'application via la commande ``docker-compose down``, à partir du répertoire racine. Relancez-la à 
tout moment avec ``docker-compose up -d``.

## Développement et tests

Le processus de développement suit les règles décrites par **Git Flow**. Pour plus d'informations,
[cet article](https://les-enovateurs.com/gitflow-workflow-git-incontournableprojets-de-qualite/) donne des détails sur 
le cadre posé par ce *workflow*.

### Gestion des bibliothèques externes

Pour accéder au répertoire de l'application au sein du **conteneur principal** ``mondegc_app``, vous pouvez taper les 
commandes suivantes dans un terminal dans le répertoire racine :
   ```
    > docker exec -ti mondegc_app /bin/bash
   ```

À partir de là, vous pouvez accéder à l'interface en ligne de commande fournie par 
[Artisan](https://laravel.com/docs/8.x/artisan), gérer les dépendances NPM et Composer, et exécuter les tests.

#### Gérer des dépendances Composer

L'application utilise [Composer](https://getcomposer.org/) afin d'installer et mettre à jour les bibliothèques qu'elle 
utilise.

Dans le conteneur principal, vous pouvez mettre à jour les bibliothèques externes décrites dans le fichier 
[composer.json](./composer.json) via la commande ``composer update``.

#### Gérer les assets CSS et JavaScript

Lorsque vous modifiez les assets JavaScript et CSS/SCSS situés dans le dossier [resources/](./resources), vous devez 
les compiler afin qu'ils soient générés dans le dossier [public/](./public) et accessibles via le navigateur Web.

Pour ce faire, toujours dans le conteneur principal, les bibliothèques JavaScript décrits dans le fichier 
[package.json](./package.json) peuvent être installés avec la commande ``npm install``.

Vous pouvez ensuite compiler les assets CSS/SCSS et JavaScript en exécutant ``npm run dev``. Le comportement de la 
compilation des assets est décrit dans le fichier [webpack.mix.js](./webpack.mix.js). Notez que les assets situés 
dans le répertoire [assets/](./assets) ne nécessitent pas d'être compilés.

### Tests

Vous pouvez exécuter les tests unitaires et fonctionnels via [PHPUnit](https://phpunit.de/), en exécutant dans le 
conteneur principal ``php artisan test``. Les tests utilisent une base de données dédiée, nommée ``mondegc_testing``. 
Leur exécution n'affectera donc pas les données présentes sur votre base de données de développement.

L'application utilise un outil d'[analyse statique](https://jolicode.com/blog/l-analyse-statique-dans-le-monde-php) du 
code fournie par [Psalm](https://psalm.dev/). La commande ``./vendor/bin/psalm`` permet d'analyser les sources à la 
recherche de problèmes liés à des erreurs de typage.

### Helpers

L'application utilise [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) afin de générer 
des *helpers*, sous forme de commentaires PHPDoc, permettant d'aider les logiciels de développement intégré à analyser 
le code et contribuer à l'autocomplétion. Il est possible de générer les propriétés et méthodes liées aux modèles 
[Eloquent](https://laravel.com/docs/8.x/eloquent) via la commande suivante. Les commentaires décrivant les propriétés 
et méthodes des modèles sont intégrés dans le bloc PHPDoc de la classe concernée.

```bash
> php artisan ide-helper:models --write
```

Il est également possible de générer un *helper* pour les façades Laravel, ainsi qu'un fichier dédié à l'autocomplétion 
par PhpStorm. Les commandes pour le faire sont décrits sur la 
[documentation de la librairie](https://github.com/barryvdh/laravel-ide-helper#readme).

## Services complémentaires

Vous pouvez accéder au site via l'adresse : [http://localhost](http://localhost). Par ailleurs, le fichier de 
configuration Docker installe des services annexes permettant de faciliter la gestion des données du site Web, décrits 
ci-dessous.

### PHPMyAdmin

Vous pouvez accéder à une instance de PHPMyAdmin, qui fournit une interface Web pour gérer la base de données, à 
l'adresse : [http://localhost:8080](http://localhost:8080). Les identifiants d'accès sont précisés dans le fichier 
[.env](.env) généré lors de l'installation de l'application.

### MailHog

MailHog fournit un serveur mail permettant de tester l'envoi de courriers électroniques sortants générés par 
l'application. MailHog est configuré pour fonctionner dès l'initialisation du conteneur Docker. Vous pouvez accéder à 
son interface Web à l'adresse : [http://localhost:8025](http://localhost:8025).

### Documentation de l'API

Vous pouvez générer la documentation de l'API des sources de l'application, via l'outil 
[phpDocumentor](https://www.phpdoc.org/). Il vous permet de créer automatiquement les pages HTML décrivant des classes 
de l'application, à partir des annotations [PHPDoc](https://fr.wikipedia.org/wiki/PHPDoc) contenues dans les sources.

Pour cela, vous pouvez exécuter la commande suivante, à partir du répertoire racine :

- sur un terminal PowerShell (Windows) :
   ```
    > docker run --rm -v ${pwd}:/data phpdoc/phpdoc:3 run
   ```
- sur un terminal Bash (Linux) :
   ```
    > docker run --rm -v $(pwd):/data phpdoc/phpdoc:3 run
   ```

Cette commande va installer l'image Docker de l'outil, et générer les pages de la documentation de l'API. Ces pages au 
format HTML seront rangés dans le dossier [docs/](./docs). Une fois générée, vous pouvez consulter la documentation à 
l'adresse [http://localhost/docs/index.html](http://localhost/docs/index.html).

Il est également possible de générer la documentation de l'[API publique](#api-publique), permettant aux autres 
services d'accéder aux données de l'application. Cette documentation est générée par 
[Scribe](https://scribe.readthedocs.io/en/latest/). Pour ce faire, vous pouvez exécuter la commande dans le conteneur 
de l'application : ```php artisan scribe:generate```. Cette commande va stocker les pages Web dans le répertoire 
[docs/public-api/](./docs/public-api).

## API publique

Le site du Monde GC fournit un service permettant d'accéder aux données de l'application, en vue d'être utilisées par 
d'autres services.

Chaque utilisateur peut générer un jeton d'authentification à partir de son compte (dans le menu "Mes pays" sur 
l'interface Web, "Gérer mon compte", puis dans la section "Outils avancés", appuyer sur "Générer").

Vous pouvez accéder à une ressource en passant le jeton en tant que 
"[Bearer Token](https://swagger.io/docs/specification/authentication/bearer-authentication/)". Un exemple de requête en 
ligne de commande avec [cURL](https://curl.se/) serait le suivant :

```
curl -i http://localhost/api/resource/fetch/pays \
-H "Authorization: Bearer Rn4u3IA3bqPqNSqhbGJkcpQFOAq2K30T5OI20wJ2D9Q55BpMco7tV1ppU0QT"
```

Pour plus d'informations sur l'API, consultez la [page dédiée](https://generation-city.com/monde/docs/public-api) !
