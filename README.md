
![Le Monde GC](https://generation-city.com/monde/assets/img/2019/logo-navbar.png)

Le Monde GC constitue l'application Web de référence du [Monde de Génération City](https://generation-city.com/monde/), un monde virtuel reposant sur les jeux de construction de ville et le jeu de rôle entre nations.

* Système de cartographie basé sur [OpenLayers 2](https://openlayers.org/two/).
* Gestion économique des pays, impliquant des infrastructures, les quêtes, la cartographie
* Groupes de pays : les organisations
* Interactivité via les communiqués, les réactions, les notifications et le système de vote intégré à l'Assemblée générale

Liens : [Site du Monde GC](https://generation-city.com/monde/) - [Le forum de la communauté](https://www.forum-gc.com/) - [Discord](https://discord.gg/4VMfsaU)

## Installation

### Prérequis

Le Monde GC nécessite d'avoir installé sur votre environnement de développement :

* **[PHP](https://www.php.net/) 7.4 ou supérieur**

* Un moteur de base de données : **[MySQL](https://www.mysql.com/fr/)** ou **[MariaDB](https://mariadb.org/)**

* Un serveur Web : **[Apache](https://httpd.apache.org/)** (conseillé) ou **[nginx](https://www.nginx.com/)**

* **[Composer](https://getcomposer.org/)**, le gestionnaire de dépendances pour PHP

* **[Node.js et npm](https://www.npmjs.com/get-npm)**, un moteur JavaScript et un gestionnaire de dépendances pour des environnements JavaScript

### Étapes
* Clonez le dépôt dans un dossier local, en ligne de commande :
    
   ```
    > git clone https://bitbucket.org/Roxayl/mondegc.git
    ```

* Créez une nouvelle base de données dans votre système de gestion de base de données (MySQL/MariaDB), qui sera utilisée par l'application.

* Copiez le fichier de configuration **/.env.example** (à la racine du site) dans un nouveau fichier **/.env**.
    * Remplissez les valeurs adaptées dans le fichier de configuration, en particulier les chemins de l'application (APP_\*), et la base de données (DB_\*).

* Installez les dépendances via Composer, depuis le dossier de l'application en ligne de commande :
    
   ```
    > composer install
    ```

* Initialisez la configuration initiale du site via la commande suivante :
    
   ```
    > php artisan monde:init
    ```

* **Si vous n'avez pas installé de serveur Web** (Apache ou nginx), servez l'application en utilisant le serveur Web intégré à PHP.
    
   ```
    > php artisan serve
    ```

* C'est prêt !

## Généralités

Le site du Monde GC est une application développée par [Calimero](https://www.forum-gc.com/u167), et lancée en 2013. Son développement a été repris par [Sakuro](https://www.forum-gc.com/u615) et [romu23](https://www.forum-gc.com/u81), avec la contribution de [Myname](https://www.forum-gc.com/u2345) et de [vallamir](https://www.forum-gc.com/u319). L'aspect graphique est réalisé par [Lesime](https://www.forum-gc.com/u23) et romu23.

Le site du Monde GC, depuis la [version 2.5](https://bitbucket.org/Roxayl/mondegc/src/release-2.5/), repose sur le framework [Laravel](https://laravel.com/), et les nouvelles fonctionnalités du site reposent sur ce framework. La [documentation](https://laravel.com/docs/8.x) de Laravel est riche et n'hésitez pas à vous renseigner sur son fonctionnement, intuitif et puissant, afin de pouvoir contribuer vous aussi au projet phare de la communauté [Génération City](http://www.forum-gc.com/).