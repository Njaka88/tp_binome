USE iran_news;

INSERT INTO users (username, password_hash, full_name)
VALUES
    ('admin', '$2y$10$7rKi7WV40i1cCtujiDcAuextj1sn1G6q0YIQcUy7lutVyZFBUd8ee', 'Administrateur BO')
ON DUPLICATE KEY UPDATE
    full_name = VALUES(full_name);

INSERT INTO categories (name, slug, description)
VALUES
    ('Diplomatie', 'diplomatie', 'Negociations, declarations officielles et arbitrages internationaux.'),
    ('Terrain militaire', 'terrain-militaire', 'Evolution des operations, zones de tension et chronologies militaires.'),
    ('Impact humanitaire', 'impact-humanitaire', 'Situation des populations civiles, deplacements et aides d urgence.'),
    ('Economie et energie', 'economie-energie', 'Impact du conflit sur les marches, les sanctions et l energie.')
ON DUPLICATE KEY UPDATE
    description = VALUES(description);

INSERT INTO articles (
    category_id, title, slug, summary, content, image_path,
    meta_title, meta_description, status, published_at
)
VALUES
(
    (SELECT id FROM categories WHERE slug = 'diplomatie' LIMIT 1),
    'Nouveau cycle de discussions multilaterales sur la deescalade',
    'discussions-multilaterales-deescalade',
    'Les acteurs regionaux relancent des discussions techniques pour reduire les risques d escalation rapide.',
    'Un nouveau cycle de discussions diplomatiques a ete lance avec un agenda centre sur la reduction des incidents frontaliers. Les delegations se concentrent sur des mecanismes de verification, la communication de crise et la protection des infrastructures civiles. Les prochaines sessions doivent clarifier les engagements de desescalade sur le terrain.',
    '/assets/img/article-diplomatie.jpg',
    'Discussions multilaterales pour la deescalade',
    'Relance des discussions diplomatiques pour freiner l escalation de la guerre en Iran.',
    'published',
    NOW() - INTERVAL 5 DAY
),
(
    (SELECT id FROM categories WHERE slug = 'terrain-militaire' LIMIT 1),
    'Recomposition des lignes de front dans le nord-ouest',
    'recomposition-lignes-front-nord-ouest',
    'La cartographie des positions evolue avec des mouvements tactiques de part et d autre.',
    'Les observateurs signalent une recomposition des lignes de front dans le nord-ouest, avec des repositionnements de materiels et un renforcement de points strategiques. Les donnees ouvertes montrent une acceleration des rotations logistiques et des restrictions de circulation dans plusieurs zones.',
    '/assets/img/article-front-nord-ouest.jpg',
    'Lignes de front: evolutions dans le nord-ouest',
    'Point de situation sur les mouvements tactiques et la recomposition des positions militaires.',
    'published',
    NOW() - INTERVAL 4 DAY
),
(
    (SELECT id FROM categories WHERE slug = 'impact-humanitaire' LIMIT 1),
    'Pression sur les couloirs humanitaires et besoins d urgence',
    'pression-couloirs-humanitaires-besoins-urgence',
    'Les organisations locales alertent sur la saturation des dispositifs de premiere assistance.',
    'La pression sur les couloirs humanitaires augmente avec des besoins prioritaires en eau, sante et abris temporaires. Les ONG demandent des fenetres de securite plus longues pour acheminer les convois. Les donnees de terrain confirment une hausse continue des deplacements internes.',
    '/assets/img/article-couloirs-humanitaires.jpg',
    'Couloirs humanitaires: besoins en hausse',
    'Analyse de la situation humanitaire et des besoins d urgence dans les zones touchees.',
    'published',
    NOW() - INTERVAL 3 DAY
),
(
    (SELECT id FROM categories WHERE slug = 'economie-energie' LIMIT 1),
    'Volatilite des prix de l energie et effets regionaux',
    'volatilite-prix-energie-effets-regionaux',
    'Les marches reagissent aux incertitudes logistiques et aux annonces de sanctions.',
    'Les fluctuations des prix de l energie s intensifient avec les annonces successives de restrictions commerciales. Les analystes observent un impact direct sur les couts de transport et la chaine d approvisionnement regionale. Plusieurs Etats ajustent leur strategie de stockage.',
    '/assets/img/article-energie.webp',
    'Prix de l energie: effets regionaux du conflit',
    'Impact economique du conflit sur les prix de l energie, la logistique et les sanctions.',
    'published',
    NOW() - INTERVAL 2 DAY
),
(
    (SELECT id FROM categories WHERE slug = 'diplomatie' LIMIT 1),
    'Canaux de mediation: quelles options realistement ouvertes',
    'canaux-mediation-options-ouvertes',
    'Plusieurs formats de mediation sont evoques, mais les conditions politiques restent fragiles.',
    'Les canaux de mediation varient entre formats bilateraux discrets et forums multilateraux plus visibles. Les experts notent que la credibilite des engagements dependra de la capacite a instaurer des verifications independantes et un calendrier concret de mesures progressives.',
    '/assets/img/article-mediation.jpg',
    'Canaux de mediation dans la guerre en Iran',
    'Etat des options de mediation et conditions de mise en oeuvre d une desescalade durable.',
    'published',
    NOW() - INTERVAL 1 DAY
),
(
    (SELECT id FROM categories WHERE slug = 'terrain-militaire' LIMIT 1),
    'Note de situation hebdomadaire: zones sous surveillance accrue',
    'note-situation-hebdomadaire-zones-surveillance',
    'Synthese hebdomadaire des zones de surveillance, incidents et changements tactiques.',
    'Cette note de situation compile les informations de la semaine sur les zones sous surveillance accrue, les incidents signales et les ajustements tactiques majeurs. Elle vise a fournir un cadre de lecture synthese pour la suite de la couverture editoriale.',
    '/assets/img/article-note-situation.webp',
    'Note hebdomadaire des zones sous surveillance',
    'Synthese hebdomadaire des principaux changements tactiques dans le conflit iranien.',
    'published',
    NOW()
)
ON DUPLICATE KEY UPDATE
    summary = VALUES(summary),
    content = VALUES(content),
    image_path = VALUES(image_path),
    meta_title = VALUES(meta_title),
    meta_description = VALUES(meta_description),
    status = VALUES(status),
    published_at = VALUES(published_at);

INSERT INTO tags (name, slug)
VALUES
    ('Deescalade', 'deescalade'),
    ('Negociations', 'negociations'),
    ('Humanitaire', 'humanitaire'),
    ('Energie', 'energie')
ON DUPLICATE KEY UPDATE
    name = VALUES(name);

INSERT IGNORE INTO article_tags (article_id, tag_id)
SELECT a.id, t.id
FROM articles a
JOIN tags t ON t.slug IN ('deescalade', 'negociations')
WHERE a.slug = 'discussions-multilaterales-deescalade';

INSERT IGNORE INTO article_tags (article_id, tag_id)
SELECT a.id, t.id
FROM articles a
JOIN tags t ON t.slug = 'humanitaire'
WHERE a.slug = 'pression-couloirs-humanitaires-besoins-urgence';

INSERT IGNORE INTO article_tags (article_id, tag_id)
SELECT a.id, t.id
FROM articles a
JOIN tags t ON t.slug = 'energie'
WHERE a.slug = 'volatilite-prix-energie-effets-regionaux';
