# Iran News Portal - Mini-projet Web design 2026

Projet binome: site d'informations sur la guerre en Iran avec FrontOffice, BackOffice, base de donnees, SEO et URL rewriting.

## Fonctionnalites livrees

- FrontOffice public:
  - accueil
  - liste d'articles paginee
  - detail article
  - page categorie
  - page 404
- BackOffice securise:
  - login/logout admin
  - CRUD categories
  - CRUD articles (incluant champs SEO)
  - upload image article
- SEO:
  - balises title/meta description
  - hierarchy h1-h6
  - alt sur images
  - canonical
  - robots.txt
  - sitemap.xml dynamique
- Technique:
  - URLs propres via Apache rewrite
  - architecture PHP + PDO + MySQL
  - docker-compose complet

## Architecture du projet

- `app/` logique applicative
- `config/` configuration
- `public/` point d'entree web + assets
- `views/` templates FO/BO
- `database/` schema et seed SQL
- `docker/` image Apache/PHP et vhost
- `docs/` documentation technique

## Lancement avec Docker

1. Copier `.env.example` vers `.env`.
2. Adapter les variables si besoin.
3. Lancer:

```bash
docker compose up -d --build
```

4. Ouvrir:
   - FO: http://localhost:8080
   - BO: http://localhost:8080/admin/login

## Compte BackOffice par defaut

- Username: `admin`
- Password: `admin123`

## Donnees de base

- Le schema est dans `database/schema.sql`.
- Les donnees de demo sont dans `database/seed.sql`.

## Notes de dev local (sans Docker)

- Configurer Apache avec DocumentRoot sur `public/`.
- Activer `mod_rewrite`.
- Creer une base MySQL `iran_news` puis importer `schema.sql` et `seed.sql`.

## Documentation technique

Voir `docs/document-technique.md`.
