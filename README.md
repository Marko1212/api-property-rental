# API-PROPERTY-RENTAL

API REST pour gérer la location des biens immobiliers

## Technologies back end utilisées

PHP 8.1.1, Symfony 6.1.4, API Platform 2.6, MySQL 8.0

## Installation

Pour démarrer le projet, après l'avoir téléchargé, il faut : installer les dépendances, configurer l'accès à la base de données (en précisant son URL dans le fichier .env), créer la base de données, exécuter les migrations afin de créer les tables dans cette BD, exécuter les fixtures pour remplir les tables avec des données. Enfin, il faut démarrer le serveur.

Exécuter dans le dossier du projet les commandes suivantes:

```bash
composer install
php bin/console doctrine:database:create
symfony console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load --no-interaction
symfony server:start
```

Ensuite, pour voir les endpoints, saisir dans la barre d'adresse du navigateur:

```bash
http://localhost:8000/api
```

Création de la base de données pour les tests:

```bash
symfony console --env=test doctrine:database:create
```

Migrations pour créer les tables dans la base de données de test:

```bash
symfony console --env=test doctrine:migrations:migrate
```

Fixtures pour la base de données de test:

```bash
symfony console --env=test doctrine:fixtures:load --no-interaction
```

Exécuter les tests avec phpunit: 

```bash
php bin/phpunit
```
