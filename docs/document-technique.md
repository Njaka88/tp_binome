# Document technique - Mini-projet Web design 2026

## 1. Identification

- Projet: Iran News Portal - site d information sur la guerre en Iran
- Cadre: Mini-projet Web design 2026
- Binome:
  - Etudiant 1: NOM PRENOM - NUM_ETU_A_COMPLETER
  - Etudiant 2: NOM PRENOM - NUM_ETU_A_COMPLETER
- Date de rendu: 31/03/2026

## 2. Resume du projet

Ce projet propose une plateforme web composee de deux espaces:

- Un FrontOffice public pour consulter les articles.
- Un BackOffice securise pour administrer les contenus.

Les objectifs pedagogiques couverts sont:

- modelisation de base de donnees relationnelle
- developpement web PHP avec architecture organisee
- URL rewriting Apache
- SEO on-page et SEO technique
- livraison reproductible via Docker Compose

## 3. Perimetre fonctionnel

### 3.1 FrontOffice

- Page accueil avec derniers articles publies
- Liste des articles paginee
- Detail article accessible par slug
- Liste des articles par categorie
- Page 404 personnalisee

### 3.2 BackOffice

- Authentification administrateur (login/logout)
- Dashboard de synthese
- CRUD categories
- CRUD articles
- Upload image article (jpg, png, webp)
- Gestion champs SEO article

## 4. Choix techniques

- Langage: PHP 8.3
- Serveur web: Apache 2.4 (mod_rewrite active)
- Base de donnees: MySQL 8.4
- Acces DB: PDO + requetes preparees
- Session/auth: session PHP + verification CSRF sur formulaires POST
- Conteneurisation: Docker Compose (2 services: app + db)

## 5. Architecture du projet

- app/
  - Controllers/ (controle des routes et actions)
  - Core/ (Router, Request, Database)
  - Repositories/ (acces SQL)
  - Services/ (authentification)
  - helpers.php (fonctions globales: render, redirect, csrf, slug)
- config/
  - config.php (config application et DB)
- public/
  - index.php (point d entree)
  - .htaccess (rewrite)
  - robots.txt
  - assets/
- views/
  - front/
  - back/
  - partials/
- database/
  - schema.sql
  - seed.sql
- docker/
  - apache/Dockerfile
  - apache/vhost.conf
- docs/
  - document-technique.md
  - screenshots/

## 6. Base de donnees

### 6.1 Tables implementees

- users
- categories
- articles
- media
- tags
- article_tags

### 6.2 Relations

- categories 1 -> N articles (fk articles.category_id, ON DELETE SET NULL)
- articles 1 -> N media (fk media.article_id, ON DELETE CASCADE)
- articles N <-> N tags via article_tags

### 6.3 Champs SEO article

- slug (unique)
- meta_title (VARCHAR 60)
- meta_description (VARCHAR 160)
- status (draft/published)
- published_at (datetime)

### 6.4 Index et contraintes

- index sur slug article
- index compose sur status + published_at
- index sur category_id
- contraintes FK sur l ensemble des relations

## 7. Authentification et securite

- Ecran de login sur /admin/login
- Session requise pour toutes les routes BackOffice
- Verification CSRF sur toutes les actions POST sensibles
- Echapement des sorties HTML cote vue
- Requetes preparees cote repositories
- Upload limite aux formats image autorises et taille max 2 Mo

Compte administrateur de demo:

- Username: admin
- Password: admin123

## 8. URL rewriting et routage

Regles principales via public/.htaccess:

- redirection 301 pour suppression de slash final incoherent
- bypass des fichiers/dossiers physiques
- redirection de toutes les routes applicatives vers public/index.php

Exemples de routes SEO:

- /
- /articles?page=1
- /article/discussions-multilaterales-deescalade
- /categorie/diplomatie?page=1
- /admin/login

## 9. SEO implemente

### 9.1 SEO on-page

- title dynamique par page
- meta description dynamique
- canonical dynamique
- hierarchie de titres h1/h2/h3
- attribut alt sur les images

### 9.2 SEO technique

- robots.txt disponible
- sitemap.xml dynamique genere depuis contenus publies
- gestion des statuts HTTP 200, 301 et 404
- URLs lisibles et stables

## 10. Donnees de demonstration

Les scripts SQL fournis permettent un jeu de donnees de test immediat:

- categories initiales
- articles publies
- utilisateur BO par defaut
- tags + associations article_tags

Fichiers:

- database/schema.sql
- database/seed.sql

## 11. Lancement du projet

### 11.1 Pre-requis

- Docker Desktop installe
- Docker Compose disponible

### 11.2 Procedure Docker

1. Copier .env.example vers .env
2. Verifier les variables de connexion DB
3. Executer: docker compose up -d --build
4. Verifier les services: docker compose ps

Acces:

- FrontOffice: http://localhost:8080
- BackOffice: http://localhost:8080/admin/login

### 11.3 Arret et reinitialisation

- Arret: docker compose down
- Reinit complete DB: docker compose down -v puis docker compose up -d --build

## 12. Procedure de recette

### 12.1 Recette fonctionnelle

1. Ouvrir accueil FrontOffice
2. Verifier liste articles + pagination
3. Ouvrir detail article
4. Ouvrir page categorie
5. Tester URL invalide -> page 404
6. Se connecter au BackOffice
7. Creer, modifier, publier et supprimer un article
8. Creer, modifier et supprimer une categorie
9. Verifier qu un article publie apparait en FrontOffice

### 12.2 Recette SEO

1. Verifier title/meta description/canonical sur plusieurs pages
2. Verifier presence des alt image
3. Verifier robots.txt
4. Verifier sitemap.xml
5. Verifier redirections 301 et statuts 404

## 13. Captures ecran a fournir

Deposer les fichiers dans docs/screenshots/:

- fo-accueil.png
- fo-articles.png
- fo-detail-article.png
- bo-login.png
- bo-dashboard.png
- bo-articles.png
- bo-form-article.png

## 14. Lighthouse (a completer)

Realiser 2 audits Lighthouse:

- profil Mobile
- profil Desktop

Reporter les scores ci-dessous:

### 14.1 Mobile

- Performance: A_COMPLETER
- Accessibilite: A_COMPLETER
- Best Practices: A_COMPLETER
- SEO: A_COMPLETER

### 14.2 Desktop

- Performance: A_COMPLETER
- Accessibilite: A_COMPLETER
- Best Practices: A_COMPLETER
- SEO: A_COMPLETER
