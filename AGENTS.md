# AGENTS.md

Ce fichier fournit des indications à un assistant IA de développement lorsqu'il travaille sur le code de ce dépôt.

## Aperçu du projet

Le Monde GC est l'application Web de « Génération City », un jeu de construction de ville et de rôle entre nations. C'est une application Laravel 10 (PHP 8.1) qui encapsule et remplace progressivement une base de code PHP historique (`legacy/`). La plupart des termes métier restent en français dans le code (pays, ville, infrastructure, patrimoine, communiqué, organisation) — conservez cette convention pour tout nouveau modèle, route ou variable liée à ce domaine.

## Commandes

Les commandes s'exécutent normalement dans le conteneur Docker de développement (`docker compose -p mondegc_dev exec app /bin/bash` depuis `.devcontainer/`), mais fonctionnent de la même façon en local si PHP 8.1 + Composer + une base MySQL/MariaDB sont disponibles.

- Installer les dépendances PHP : `composer install`
- Installer les dépendances JS : `npm install`
- Compiler les assets (dev) : `npm run dev` (alias de `npm run development`, exécute Laravel Mix via `webpack.mix.js`)
- Compilation en continu : `npm run watch` / avec rechargement à chaud : `npm run hot`
- Compiler les assets (prod) : `npm run prod`
- Lancer toute la suite de tests : `php artisan test` (ou `./vendor/bin/phpunit`)
- Lancer un seul fichier de test : `php artisan test tests/Feature/UserVisitsPageTest.php`
- Lancer une seule méthode de test : `php artisan test --filter=testMethodName`
- Analyse statique : `./vendor/bin/psalm` (errorLevel 7, config dans `psalm.xml` ; n'analyse que `app/`, `database/factories`, `database/seeders` — `legacy/` et `vendor/` sont ignorés)
- Style : vérifié par StyleCI (`.styleci.yml`, preset Laravel) sur CI — pas d'outil CLI local configuré au-delà de StyleCI lui-même.
- Régénérer les helpers IDE des modèles Eloquent après modification d'un modèle : `php artisan ide-helper:models --write`

Commandes `artisan` personnalisées à connaître (`app/Console/Commands/`) :
- `monde:init-env` — génère l'environnement/les clés pour une nouvelle installation de dev.
- `monde:init-db` — initialise la base de données (crée un utilisateur `Admin`/`password` par défaut).
- `monde:init-testing` — génère `.env.testing` (requis avant de lancer les tests ; PHPUnit refuse de s'exécuter sans lui, pour éviter d'écraser la base de dev — voir `bootstrap/test.php`).
- `monde:override-vendor` — copie des fichiers de `app/Overrides/` par-dessus certains fichiers de `vendor/`, patchant des paquets tiers sur place (actuellement `SearchResult` de `spatie/laravel-searchable`). Exécutée automatiquement lors de `composer install`/`update` via les scripts composer ; à relancer manuellement si un fichier vendor patché est réinitialisé.
- `monde:regenerate-influences` — recalcule les enregistrements `Influence` (voir les services de gameplay ci-dessous).
- `monde:generate-htaccess` — régénère le `.htaccess`.
- `monde:update-project` — utilitaire de mise à jour/déploiement du projet.

Les tests se trouvent uniquement dans `tests/Feature` (la suite `Unit` existe mais est commentée dans `phpunit.xml.dist`). `tests/Feature/Legacy/` couvre spécifiquement le pont avec le code legacy.

## Architecture

### Le pont avec le code legacy

L'application est une réécriture progressive en Laravel d'un ancien site PHP procédural. Les deux coexistent :
- `legacy/php/` (autoloadé sous le namespace `GenCity\`, point d'entrée dans `legacy/php/init`) contient la logique du site historique (Monde, Proposal, elements pour infrastructure/institut/logger/pays/proposal/temperance).
- `legacy/back/` contient d'autres ressources backend historiques.
- `app/Http/Controllers/Legacy/LegacySiteController.php` et les routes de `routes/web.php` font le pont vers les pages PHP historiques (par ex. login/logout redirigent vers `connexion.php` car l'authentification native de Laravel n'est pas utilisée — l'authentification reste gérée par le système legacy).
- `app/Services/Legacy/` et `app/Overrides/` contiennent les adaptateurs/patches permettant au code moderne d'interagir avec la couche legacy.

Pour toute modification touchant l'authentification, les sessions, ou tout ce qui est sous `legacy/`, vérifiez le côté Laravel ET le côté PHP legacy — le comportement est souvent réparti entre les deux.

### Organisation du modèle de domaine

`app/Models/` contient les modèles Eloquent des entités principales du jeu : `Pays`, `Ville`, `Subdivision`/`SubdivisionType`, `Organisation`/`OrganisationMember`, `Infrastructure`/`InfrastructureGroupe`/`InfrastructureOfficielle`, `Patrimoine`, `Chapter`/`ChapterEntry`/`ChapterResourceable` (chapitres de rôleplay), `Communique`, `Roleplay`, `Personnage`, `Influence`, `Geometry`/`TypeGeometry`/`TypeGeometriesGroup` (données cartographiques), `Page`, `Version`, `CustomUser` (le modèle utilisateur de l'application — pas le `User` par défaut de Laravel), `Log`, `ResourceHistory`, `NotificationLegacy`, `DiscordNotification`, `OcgcProposal`, `MonumentCategory`.

Couches complémentaires autour des modèles :
- `app/Models/Managers/` — classes manager opérant sur les modèles (logique métier qui n'a pas sa place directement sur le modèle Eloquent).
- `app/Models/Presenters/` — logique de présentation/formatage découplée des modèles.
- `app/Models/Repositories/` — abstractions d'accès aux données/requêtes.
- `app/Models/Traits/`, `app/Models/Contracts/`, `app/Models/Enums/`, `app/Models/Factories/` — comportements partagés, interfaces, enums et factories de modèles (présentes ici, en plus de `database/factories/`).

Versionning : de nombreuses entités utilisent `mpociot/versionable` pour l'historique des révisions (voir le modèle `Version` / `ResourceHistory`).

### Services

`app/Services/` regroupe la logique transversale de l'application :
- `Discord/` — intégration du bot/des webhooks Discord (notifications, rôles) — voir les clés `DISCORD_*` du `.env`.
- `Gameplay/` — règles de jeu (économie, infrastructures, influence, etc.).
- `Legacy/` — code de pont/adaptation vers la couche PHP legacy.
- `Version/` — support du versionning au-dessus de `mpociot/versionable`.
- `AuthenticationService.php`, `HelperService.php`, `StringBladeService.php` — services généraux de l'application.

`app/Jobs/` (dont `Jobs/Discord/`) contient les jobs mis en file d'attente ; la connexion de queue par défaut est `sync` en environnement de test.

`app/Events/` et `app/Listeners/` implémentent le système d'événements du domaine (organisés par domaine : `Infrastructure`, `Organisation`, `Patrimoine`, `Pays` ; les listeners sont eux-mêmes répartis entre les préoccupations `Influence` et `Notification`). Enregistrez les nouvelles paires événement/listener dans `app/Providers/EventServiceProvider.php`.

### Couche HTTP

- Les contrôleurs sont dans `app/Http/Controllers/`, avec `Api/` (endpoints de l'API publique, authentifiés par jeton Bearer — voir la section « API publique » du README) et `Legacy/` (contrôleur de pont) comme sous-groupes notables.
- `routes/web.php` est le fichier de routes principal ; `routes/api.php` est réduit et n'expose que la surface de l'API publique.
- L'API publique permet aux utilisateurs de récupérer des ressources du jeu (ex. `GET /api/resource/fetch/pays`) à l'aide d'un jeton Bearer personnel généré depuis leur page de compte. La documentation de l'API est générée avec `knuckleswtf/scribe` (voir `.scribe/`).
- Les policies (`app/Policies/`) gèrent les autorisations ; `app/Http/Requests/` contient les classes de validation des form requests.

### Cartographie

La carte interactive utilise OpenLayers 2, alimentée par une pyramide de tuiles téléchargée séparément (absente du dépôt — voir le README pour les archives `carto-full.zip` / `carto-light.zip`, à extraire à la racine du projet). Les données cartographiques du domaine sont dans `Geometry`, `TypeGeometry`, `TypeGeometriesGroup`, et `MapController`.

### Feature flags

Le projet utilise `ylsideas/feature-flags`, configuré via `.features.php` à la racine du dépôt (et non dans `config/` comme par défaut).

## Notes sur les tests

- `bootstrap/test.php` est le bootstrap PHPUnit et échoue explicitement si `.env.testing` est absent, précisément pour éviter que les tests s'exécutent sur (et corrompent) la base de données de dev locale. Exécutez `php artisan monde:init-testing` une fois par environnement avant de lancer les tests.
- Les tests utilisent par défaut la base `mondegc_testing` (MySQL/MariaDB réel, pas sqlite — les lignes sqlite de `phpunit.xml.dist` sont commentées).
- `brianium/paratest` est disponible pour exécuter les tests en parallèle si besoin.
