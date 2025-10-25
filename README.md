# Gamehub - back
## Description
Backend Laravel du projet Gamehub. Contient toutes les API pour le front-end.
## Installation
### Mise en place des variables d'environnement
1. Copier le fichier `.env.example` en `.env` :
```bash
cp .env.example .env
```
2. Modifier les variables d'environnement dans le fichier `.env` :
   1. Variable liées à l'application (`APP_*`) 
   2. Variables liées à la base de données (`DB_*`)
   3. Variables liées à Meilisearch (`MEILISEARCH_HOST`, `MEILISEARCH_PORT`, `MEILISEARCH_KEY`)
## Lancer le projet
### Manuellement
#### Prérequis
- PHP ^8.1
- Node ^18 avec npm
- [Meilisearch](https://www.meilisearch.com/docs/learn/self_hosted/getting_started_with_self_hosted_meilisearch)
- Base de donnée supportée par Laravel (MySQL, PostgreSQL, SQLite, SQL Server)
- .env configuré correctement avec la base de données et Meilisearch
##### Installation des dépendances
```bash
composer install
```
Installation des dépendances de link-preview-server
```bash
cd link-preview-server
npm install && cd ..
```
#### Commandes
1. ##### Lancer Meilisearch
Dépend de l'installation, si vous avez installé Meilisearch en tant qu'exécutables, vous pouvez lancer la commande suivante :
```bash
/[PATH_TO_MEILISEARCH]/meilisearch --master-key=[MEILISEARCH_KEY]
```
Où `[PATH_TO_MEILISEARCH]` est le chemin vers l'exécutable de Meilisearch et `[MEILISEARCH_KEY]` est la clé choisie pour Meilisearch qui est une copie de celle présente dans le fichier `.env`.
2. ##### Lancer le serveur Laravel
Dans un autre terminal, lancez le serveur Laravel :
```bash
php artisan serve
```
3. ##### Lancer le serveur d'aperçu de page web
Dans un autre terminal :
```bash
php artisan serve:startLinkPreview
```
4. ##### Lancer les migrations`
```bash
php artisan migrate
```
5. ##### Lancer les seeders
Avec des données de test :
```bash
php artisan db:seed
```
Avec uniquement le strict nécessaire :
```bash
php artisan db:seed --class=RequiredSeeder
```
### Avec Docker Compose
```bash
docker compose up
```
