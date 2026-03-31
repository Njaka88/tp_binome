# TODO detaille - Mini-projet Web design 2026

Objectif global:
- Realiser un site d'informations sur la guerre en Iran avec:
1. FrontOffice (consultation)
2. BackOffice (gestion des contenus)
3. Base de donnees
4. URL rewriting
5. Optimisation SEO
6. Livraison docker + repo public + document technique

Contrainte delai:
- Deadline: mardi 31 mars a 14h00

---

## 1) Cadrage rapide (a faire en premier)

- [ ] Lire et valider le perimetre avec le binome (30 min)
- [ ] Choisir la stack (exemple: PHP 8 + MySQL + Apache) (15 min)
- [ ] Definir la convention de nommage des URLs SEO (15 min)
- [ ] Initialiser le repo git (branche `main` + README de base) (15 min)
- [ ] Creer la structure du projet:
  - [ ] `front/`
  - [ ] `back/`
  - [ ] `config/`
  - [ ] `public/`
  - [ ] `docker/`
  - [ ] `docs/`

Critere de validation:
- [ ] Tous les membres savent qui fait quoi et dans quel ordre.

---

## 2) Modelisation et base de donnees

### 2.1 Modele de donnees
- [ ] Definir les entites minimales:
  - [ ] `users` (auth BO)
  - [ ] `categories`
  - [ ] `articles`
  - [ ] `media` (images)
  - [ ] `tags` (optionnel mais recommande)
  - [ ] table pivot `article_tags` (si tags)
- [ ] Ajouter les champs SEO dans `articles`:
  - [ ] `slug` unique
  - [ ] `meta_title`
  - [ ] `meta_description`
  - [ ] `canonical_url` (optionnel)
  - [ ] `status` (draft/published)
  - [ ] `published_at`

### 2.2 Script SQL
- [ ] Ecrire `schema.sql` (create tables + contraintes)
- [ ] Ecrire `seed.sql`:
  - [ ] 1 user BO par defaut
  - [ ] categories initiales
  - [ ] 5-10 articles de test
- [ ] Verifier les index utiles:
  - [ ] index sur `slug`
  - [ ] index sur `published_at`
  - [ ] index sur FK

Critere de validation:
- [ ] Import SQL sans erreur sur une base vide.
- [ ] Donnees de demo visibles et coherentes.

---

## 3) BackOffice (gestion des contenus)

### 3.1 Authentification
- [ ] Ecran de login BO
- [ ] Session securisee (cookie session, logout)
- [ ] Proteger toutes les routes BO
- [ ] Definir user/pass par defaut pour la livraison

### 3.2 CRUD contenus
- [ ] CRUD categories
- [ ] CRUD articles
  - [ ] titre
  - [ ] resume
  - [ ] contenu
  - [ ] image
  - [ ] slug SEO
  - [ ] meta title / meta description
  - [ ] statut publication
- [ ] Gestion upload image (taille/max type)

### 3.3 Ergonomie BO
- [ ] Tableau liste articles (filtre statut/date)
- [ ] Message succes/erreur sur actions CRUD
- [ ] Validation formulaire serveur

Critere de validation:
- [ ] Un utilisateur BO peut creer/modifier/publier/supprimer un article.
- [ ] Aucune page BO accessible sans login.

---

## 4) FrontOffice (affichage public)

### 4.1 Pages minimales
- [ ] Page accueil (articles recents)
- [ ] Page liste articles (pagination)
- [ ] Page detail article
- [ ] Page categorie
- [ ] Page 404 propre

### 4.2 Rendu SEO-friendly
- [ ] 1 seul `h1` par page
- [ ] Hierarchie `h2`...`h6` logique
- [ ] Balise `<title>` unique par page
- [ ] Balise `<meta name="description">` pertinente
- [ ] Attribut `alt` sur toutes les images

Critere de validation:
- [ ] Un article publie en BO apparait correctement en FO.
- [ ] La structure HTML respecte les regles SEO de base.

---

## 5) URL rewriting (obligatoire)

- [ ] Activer `mod_rewrite` (Apache)
- [ ] Ajouter un fichier `.htaccess`
- [ ] Definir les routes SEO:
  - [ ] `/article/{slug}`
  - [ ] `/categorie/{slug}`
  - [ ] `/page/{n}` (si pagination)
- [ ] Gerer redirections:
  - [ ] URL ancienne vers URL canonique (301)
  - [ ] suppression trailing slash incoherent
- [ ] Tester les cas invalides vers 404

Critere de validation:
- [ ] Aucune URL avec query string technique exposee au public pour les pages principales.
- [ ] Les URLs sont lisibles, stables et indexables.

---

## 6) SEO technique et contenu

### 6.1 Essentiels SEO on-page
- [ ] `title` entre 50 et 60 caracteres (approx)
- [ ] `meta description` entre 150 et 160 caracteres (approx)
- [ ] URL courtes avec mot-cle
- [ ] Liens internes coherents entre articles/categories

### 6.2 Essentiels SEO technique
- [ ] Creer `robots.txt`
- [ ] Generer `sitemap.xml`
- [ ] Ajouter balise canonique sur les pages critiques
- [ ] Verifier codes HTTP (200, 301, 404)

### 6.3 Performance / Lighthouse
- [ ] Optimiser images (poids + dimensions)
- [ ] Minifier CSS/JS si possible
- [ ] Limiter scripts bloquants
- [ ] Lancer test Lighthouse local:
  - [ ] Mobile
  - [ ] Desktop
- [ ] Capturer scores et corriger points majeurs

Critere de validation:
- [ ] Checklist SEO du sujet validee.
- [ ] Rapport Lighthouse present (captures dans doc technique).

---

## 7) Dockerisation pour livraison

- [ ] Creer `docker-compose.yml` avec au minimum:
  - [ ] service web (apache/php)
  - [ ] service db (mysql/mariadb)
- [ ] Declarer variables d'environnement (`.env.example`)
- [ ] Ajouter volume pour persistance DB
- [ ] Ajouter script init DB (`schema.sql` + `seed.sql`)
- [ ] Verifier lancement from scratch:
  - [ ] `docker compose up -d`
  - [ ] acces FO OK
  - [ ] acces BO OK

Critere de validation:
- [ ] Un tiers peut lancer le projet sans installation manuelle complexe.

---

## 8) Documentation technique (obligatoire)

- [ ] Rediger `docs/document-technique.md` contenant:
  - [ ] Description rapide du projet
  - [ ] Choix techniques
  - [ ] Schema de base (MCD/MLD ou schema SQL explique)
  - [ ] Captures ecran FO
  - [ ] Captures ecran BO
  - [ ] Login BO par defaut (user/pass)
  - [ ] Numeros ETU du binome
  - [ ] Procedure de lancement local et docker
- [ ] Ajouter section "limites connues"
- [ ] Ajouter section "ameliorations futures"

Critere de validation:
- [ ] Le correcteur peut installer, tester et comprendre le projet sans aide orale.

---

## 9) Git, qualite et livraison finale

- [ ] Creer depot public github/gitlab
- [ ] Nettoyer l'historique (commits clairs)
- [ ] Ajouter `.gitignore` (exclure `.venv`, caches, secrets)
- [ ] Verifier qu'aucun mot de passe reel n'est commit
- [ ] Preparer ZIP final avec:
  - [ ] code source
  - [ ] docker
  - [ ] doc technique
  - [ ] scripts SQL

Checklist finale avant rendu:
- [ ] FO fonctionnel
- [ ] BO fonctionnel
- [ ] Login BO fourni
- [ ] URLs rewritees
- [ ] SEO de base valide
- [ ] Lighthouse mobile + desktop testes
- [ ] Docker OK
- [ ] Repo public OK
- [ ] ZIP OK

---

## 10) Plan de travail binome (propose, tres court delai)

Jour J-2 (aujourd'hui)
- [ ] Personne A: BDD + BO auth + CRUD articles
- [ ] Personne B: FO + routing + templates + SEO tags
- [ ] Point sync toutes les 2h

Jour J-1
- [ ] Integration complete A/B
- [ ] Rewriting + tests 404/301
- [ ] Robots + sitemap + lighthouse
- [ ] Docker + doc technique

Jour J (matin)
- [ ] Recette complete sur machine propre
- [ ] Correction derniers bugs bloquants
- [ ] Generation ZIP final
- [ ] Verification lien repo public

---

## 11) Risques et parades

- [ ] Risque: perte de temps sur design visuel
  - Parade: garder UI simple, prioriser fonctionnalites et SEO
- [ ] Risque: rewriting casse les routes
  - Parade: tests manuels de toutes les URLs critiques
- [ ] Risque: docker non stable
  - Parade: tester `up/down/up` sur environnement propre
- [ ] Risque: doc incomplete
  - Parade: remplir la doc en continu, pas a la fin

---

## 12) Definition of done

Le projet est "done" si:
- [ ] Toutes les exigences du sujet sont visibles en demo
- [ ] Le projet tourne via docker sans correction manuelle
- [ ] Les preuves (captures, schema, login, ETU) sont dans la doc
- [ ] Le repo public et le ZIP sont prets avant deadline
