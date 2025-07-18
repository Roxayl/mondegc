<p align="center"><a href="https://generation-city.com/monde/" target="_blank"><img src="https://generation-city.com/monde/assets/img/2019/logo-navbar.png" width="420" alt="Monde GC"></a></p>

<p align="center">
<a href="https://github.com/Roxayl/mondegc/actions"><img src="https://github.com/Roxayl/mondegc/actions/workflows/build.yml/badge.svg" alt="CI" /></a>
<a href="https://github.styleci.io/repos/349166635?branch=main"><img src="https://github.styleci.io/repos/349166635/shield?branch=main" alt="StyleCI"></a>
<a href="https://discord.gg/4P3HqVbbgR"><img src="https://img.shields.io/discord/328496654514782209" alt="Discord" /></a>
</p>

Le Monde GC constitue l'application Web de référence du [Monde de Génération City](https://generation-city.com/monde/), 
un monde virtuel reposant sur les jeux de construction de ville et le jeu de rôle entre nations.

* Système de cartographie basé sur [OpenLayers 2](https://openlayers.org/two/)
* Gestion économique des pays, impliquant des infrastructures, les quêtes, la cartographie
* Groupes de pays : les organisations
* Interactivité via les communiqués, les réactions, les notifications et le système de vote intégré à l'Assemblée 
générale

Liens : [Site du Monde GC](https://generation-city.com/monde/) -
[Le forum de la communauté](https://www.forum-gc.com/) - [Discord](https://discord.gg/4P3HqVbbgR)

## Table des matières

- [Table des matières](#table-des-matières)
- [À propos](#à-propos)
- [Installation](#installation)
  - [Environnement](#environnement)
  - [Installation via Docker](#installation-via-docker)
    - [Installer Docker](#installer-docker)
      - [Installer Docker sur Windows](#installer-docker-sur-windows)
      - [Installer Docker sur Linux](#installer-docker-sur-linux)
    - [Installer l'application](#installer-lapplication)
  - [Télécharger les données de cartographie](#télécharger-les-données-de-cartographie)
  - [Lancement et arrêt de l'application](#lancement-et-arrêt-de-lapplication)
- [Développement et tests](#développement-et-tests)
  - [Structure des dépôts Git](#structure-des-dépôts-git)
  - [Gestion des bibliothèques externes](#gestion-des-bibliothèques-externes)
    - [Gérer des dépendances Composer](#gérer-des-dépendances-composer)
    - [Gérer les assets CSS et JavaScript](#gérer-les-assets-css-et-javascript)
  - [Tests](#tests)
  - [Helpers](#helpers)
  - [Services complémentaires](#services-complémentaires)
    - [PHPMyAdmin](#phpmyadmin)
    - [MailHog](#mailhog)
- [API publique](#api-publique)

## À propos

Le site du Monde GC est une application développée par [Calimero](https://www.forum-gc.com/u167), et lancée en 2013. 
Son développement a été repris par [Sakuro](https://www.forum-gc.com/u615) et [Roxayl](https://www.forum-gc.com/u81), 
avec la contribution de [Myname](https://www.forum-gc.com/u2345) et de [vallamir](https://www.forum-gc.com/u319). 
L'aspect graphique est réalisé par [Lesime](https://www.forum-gc.com/u23) et Roxayl.

Le site du Monde GC, depuis la [version 2.5](https://www.forum-gc.com/t6872p110-notes-de-mise-a-jour-monde-gc#287597) 
(juillet 2020), repose sur le framework [Laravel](https://laravel.com/), et les nouvelles fonctionnalités du site 
reposent sur ce framework. La [documentation](https://laravel.com/docs/9.x) de Laravel est riche et n'hésitez pas à 
vous renseigner sur son fonctionnement, intuitif et puissant. Les sources sont
[accessibles à tous](https://www.forum-gc.com/t7372-contribuez-au-site-du-monde-gc) depuis avril 2023 : venez vous 
aussi contribuer au projet phare de la communauté [Génération City](http://www.forum-gc.com/).

## Installation

### Environnement

Le Monde GC s'exécute sur un environnement de développement comprenant les logiciels suivants :

* **[PHP](https://www.php.net/) 8.1**
* Un moteur de base de données : **[MySQL](https://www.mysql.com/fr/)** (5.7 ou supérieur) ou **[MariaDB](https://mariadb.org/)** (10.3 ou supérieur)
* Un serveur Web : **[Apache](https://httpd.apache.org/)** (2.4, fortement conseillé) ou **[nginx](https://www.nginx.com/)**
(nécessite d'adapter les règles de réécriture d'URL)
* **[Composer](https://getcomposer.org/)** 2, le gestionnaire de dépendances pour PHP
* **[Node.js](https://nodejs.org) 16** et **[npm](https://www.npmjs.com/)**, un moteur JavaScript et un gestionnaire de dépendances pour des 
environnements JavaScript

### Installation via Docker

L'application fournit une configuration permettant d'exécuter l'application de manière conteneurisée, via 
[Docker](https://fr.wikipedia.org/wiki/Docker_(logiciel)). La configuration est décrite dans les fichiers 
[docker-compose.yml](./.devcontainer/docker-compose.yml) et [Dockerfile](./.devcontainer/Dockerfile).

Ce guide fournit les étapes pour démarrer l'application Web sur votre machine. Elle détaille :

- l'installation de Docker et Docker Compose
    - sur Windows
    - sur les systèmes Linux
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
   ```bash
   git clone git@github.com:Roxayl/mondegc.git
   ```

2. Lancez les conteneurs Docker de l'application via la commande suivante, depuis le répertoire ``.devcontainer`` :
   ```bash
   cd .devcontainer
   docker compose -p mondegc_dev up -d
   ```

3. Accédez au conteneur de l'application via la commande à saisir dans un terminal.
   ```bash
   docker compose -p mondegc_dev exec app /bin/bash
   ```

4. Dans le conteneur de l'application, exécutez la commande permettant d'installer les dépendances et bibliothèques
externes PHP (gérée par Composer).
   ```bash
   composer install
   ```

5. Toujours dans le conteneur de l'application, exécutez la commande d'initialisation. Cette commande va notamment 
générer des clés et d'autres variables d'environnement.
   ```bash
   php artisan monde:init-env
   ```

6. Initialisez ensuite la base de données. Enfin, vous pouvez sortir du conteneur via la commande ``exit``.
   ```bash
   php artisan monde:init-db
   exit
   ```

7. Vwalà ! Retrouvez le Monde GC à l'adresse suivante : **[http://localhost](http://localhost)**. La base de données 
est initialisée avec un utilisateur par défaut, dont vous pouvez utiliser les identifiants pour vous connecter (nom 
d'utilisateur : ``Admin``, mot de passe : ``password``).

### Télécharger les données de cartographie

La carte interactive repose sur une matrice de tuiles. Vous pouvez télécharger l'ensemble des images qui composent la 
carte à partir des liens ci-dessous. Deux archives sont proposés, une **archive complète** et une **archive allégée**, 
contenant uniquement les tuiles correpondant aux niveaux de zoom les plus faibles. Vous pouvez télécharger et extraire 
le contenu de ces archives dans le répertoire racine.

| Type d'archive   |                                    Téléchargement                                    |   Taille | Niveaux de zoom |
|:-----------------|:------------------------------------------------------------------------------------:|---------:|:---------------:|
| Archive complète |  [carto-full.zip](https://generation-city.com/monde/docs/dev-assets/carto-full.zip)  | 1 135 Mo |       1-7       |
| Archive allégée  | [carto-light.zip](https://generation-city.com/monde/docs/dev-assets/carto-light.zip) |   168 Mo |       1-4       |

### Lancement et arrêt de l'application

Vous pouvez arrêter l'application via la commande ``docker-compose -p mondegc_dev down``, à partir du répertoire racine. Relancez-la à 
tout moment avec ``docker-compose -p mondegc_dev up -d``.

## Développement et tests

Le processus de développement suit les règles décrites par **GitHub Flow**. Pour plus d'informations,
[cet article](https://www.alexhyett.com/git-flow-github-flow/#what-is-github-flow) donne des détails sur le cadre posé 
par cette stratégie de branche. Pour plus d'informations sur les modalités de contribution, consultez le document
[CONTRIBUTING.md](CONTRIBUTING.md).

### Structure des dépôts Git

Les sources du site sont gérées par Git, hébergées sur un certain nombre de plateformes.

| Plateforme |                          Dépôt                          | Complet ? | Visibilité | Commentaires                             |
|:----------:|:-------------------------------------------------------:|:---------:|:----------:|:-----------------------------------------|
|   GitHub   |   [Roxayl/mondegc](https://github.com/Roxayl/mondegc)   |    Oui    |   Public   | Dépôt principal                          |
| Bitbucket  | [Roxayl/mondegc](https://bitbucket.org/Roxayl/mondegc/) |    Oui    |   Privée   | Miroir du dépôt principal, lecture seule |

### Gestion des bibliothèques externes

Pour accéder au répertoire de l'application au sein du **conteneur principal** ``mondegc_app``, vous pouvez taper les 
commandes suivantes dans un terminal dans le répertoire ``.devcontainer`` :
```bash
docker compose -p mondegc_dev exec app /bin/bash
```

À partir de là, vous pouvez accéder à l'interface en ligne de commande fournie par 
[Artisan](https://laravel.com/docs/9.x/artisan), gérer les dépendances NPM et Composer, et exécuter les tests.

#### Gérer des dépendances Composer

L'application utilise [Composer](https://getcomposer.org/) afin d'installer et mettre à jour les bibliothèques qu'elle 
utilise.

Dans le conteneur principal (app), vous pouvez mettre à jour les bibliothèques externes décrites dans le fichier 
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
conteneur principal ``php artisan test``. L'environnement de test utilise les variables présentes dans le fichier 
``.env.testing``, que vous pouvez générer via la commande ``php artisan monde:init-testing``. Par défaut, les tests 
utilisent une base de données dédiée, nommée ``mondegc_testing``. Leur exécution n'affectera donc pas les données 
présentes sur votre base de données de développement.

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
php artisan ide-helper:models --write
```

Il est également possible de générer un *helper* pour les façades Laravel, ainsi qu'un fichier dédié à l'autocomplétion 
par PhpStorm. Les commandes pour le faire sont décrits sur la 
[documentation de la librairie](https://github.com/barryvdh/laravel-ide-helper#readme).

### Services complémentaires

Vous pouvez accéder au site via l'adresse : [http://localhost](http://localhost). Par ailleurs, le fichier de 
configuration Docker installe des services annexes permettant de faciliter la gestion des données du site Web, décrits 
ci-dessous.

#### PHPMyAdmin

Vous pouvez accéder à une instance de PHPMyAdmin, qui fournit une interface Web pour gérer la base de données, à 
l'adresse : [http://localhost:8080](http://localhost:8080). Les identifiants d'accès sont précisés dans le fichier 
[.env](.env) généré lors de l'installation de l'application.

#### MailHog

MailHog fournit un serveur mail permettant de tester l'envoi de courriers électroniques sortants générés par 
l'application. MailHog est configuré pour fonctionner dès l'initialisation du conteneur Docker. Vous pouvez accéder à 
son interface Web à l'adresse : [http://localhost:8025](http://localhost:8025).

## API publique

Le site du Monde GC fournit un service permettant d'accéder aux données de l'application, en vue d'être utilisées par 
d'autres services.

Chaque utilisateur peut générer un jeton d'authentification à partir de son compte (dans le menu "Mes pays" sur 
l'interface Web, "Gérer mon compte", puis dans la section "Outils avancés", appuyer sur "Générer").

Vous pouvez accéder à une ressource en passant le jeton en tant que 
"[Bearer Token](https://swagger.io/docs/specification/authentication/bearer-authentication/)". Un exemple de requête en 
ligne de commande avec [cURL](https://curl.se/) serait le suivant :

```bash
curl -i http://localhost/api/resource/fetch/pays \
-H "Authorization: Bearer Rn4u3IA3bqPqNSqhbGJkcpQFOAq2K30T5OI20wJ2D9Q55BpMco7tV1ppU0QT"
```

Pour plus d'informations sur l'API, consultez la [page dédiée](https://generation-city.com/monde/docs/public-api) !
