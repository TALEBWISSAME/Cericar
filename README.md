# CeriCar

CeriCar est une application web universitaire de covoiturage realisee avec
PHP, Yii2, JavaScript et PostgreSQL.

## Fonctionnalites

- inscription, connexion et gestion de session ;
- recherche de voyages par ville de depart et d'arrivee ;
- proposition d'un voyage par un conducteur ;
- reservation de places avec verification des places restantes ;
- profil regroupant voyages proposes et reservations ;
- interactions AJAX pour les recherches, inscriptions et reservations.

## Architecture

Le projet utilise l'architecture MVC de Yii2 :

- `models/` contient les modeles ActiveRecord et les regles metier ;
- `controllers/` traite les requetes et coordonne les actions ;
- `views/` contient les interfaces utilisateur ;
- `web/js/` contient les interactions AJAX cote client.

## Base de donnees

La base PostgreSQL et le schema `fredouil` etaient fournis et heberges sur
l'environnement informatique de l'universite. Ils ne sont pas inclus dans ce
depot.

La connexion se configure avec les variables d'environnement suivantes :

```text
CERICAR_DB_DSN
CERICAR_DB_USER
CERICAR_DB_PASSWORD
CERICAR_COOKIE_KEY
```

Exemple de DSN :

```text
pgsql:host=localhost;port=5432;dbname=etd
```

## Installation

```bash
composer install
php yii serve
```

L'application est ensuite disponible sur `http://localhost:8080`.

Les parcours utilisant les donnees necessitent une base compatible avec les
tables attendues par les modeles du dossier `models/`.
