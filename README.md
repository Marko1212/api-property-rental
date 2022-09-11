# API-PROPERTY-RENTAL

API REST pour gérer la location des biens immobiliers

## Technologies back end utilisées

PHP 8.1.1, Symfony 6.1.4, API Platform 2.6, MySQL 8.0

## Installation

Pour démarrer le projet, après l'avoir téléchargé, il faut installer les dépendances, configurer l'accès à la base de données, en précisant son URL dans le fichier .env, créer la base de données, exécuter les migrations, exécuter les fixtures et lancer le serveur.

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

Pour les tests, il faut saisir: 

```bash
php bin/phpunit
```
