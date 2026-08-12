# Déploiement COMCO sur Hostinger (comcordc.cd)

Ce dossier contient les scripts pour déployer l'application Laravel sur Hostinger.

## Critique : thème Elixir (`public/theme`)

Sans `public/theme`, le site public s'affiche **sans CSS** (HTML brut).

Le thème est versionné dans Git (`public/theme`, ~35 Mo). Après tout déploiement, vérifier :

```bash
test -f public/theme/assets/css/theme.min.css && echo THEME_OK || echo THEME_MISSING
curl -sI https://comcordc.cd/theme/assets/css/theme.min.css | head -1
```

### Réparer le thème manquant sur le serveur

```bash
cd /home/u911414181/domains/comcordc.cd/public_html
curl -fsSL -o install-theme-once.php https://raw.githubusercontent.com/silasmas/comco/main/.deploy/install-theme-once.php
php install-theme-once.php
```

## Structure Hostinger

- Document root hPanel : `public_html` (pas `public_html/public`)
- Fichiers racine versionnés : `index.php` + `.htaccess` (réécrivent vers `public/`)
- Assets servis : `public_html/public/{theme,assets,build,...}`

## Prérequis

- PHP 8.3+, Composer, Node.js/npm en local
- Accès SSH activé dans hPanel (port **65002**)
- Mot de passe FTP/SSH du compte `u911414181`

## Après le déploiement

1. Vérifier `THEME_OK` (commande ci-dessus)
2. Site public : `https://comcordc.cd`
3. Admin : `https://comcordc.cd/admin/login`
4. `comcordc.org` / `comcordc.com` redirigent vers `comcordc.cd`

## Base de données

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u911414181_comco
DB_USERNAME=u911414181_comco
```

Le mot de passe DB doit être défini dans `.deploy/env.production` (non versionné).
