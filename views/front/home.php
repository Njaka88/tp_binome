<?php require BASE_PATH . '/views/partials/front_header.php'; ?>

<?php $defaultImage = '/assets/img/news-placeholder.svg'; ?>

<section class="hero">
    <p class="eyebrow">Dossier special</p>
    <h1>Guerre en Iran: actualites, contexte et analyse</h1>
    <p>
        Suivez les developments majeurs, les impacts regionaux et les analyses de fond.
        Contenus structures pour une lecture rapide et une bonne indexation SEO.
    </p>
    <a class="button" href="<?= e(base_url('/articles')) ?>">Voir tous les articles</a>
</section>

<?php if (!empty($categories)): ?>
    <section>
        <h2>Categories editoriales</h2>
        <div class="chip-row">
            <?php foreach ($categories as $category): ?>
                <a class="chip" href="<?= e(base_url('/categorie/' . $category['slug'])) ?>">
                    <?= e($category['name']) ?> (<?= e((string) $category['published_count']) ?>)
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section>
    <h2>Derniers articles publies</h2>
    <?php if (empty($articles)): ?>
        <p>Aucun article publie pour le moment.</p>
    <?php else: ?>
        <div class="grid cards">
            <?php foreach ($articles as $article): ?>
                <article class="card">
                    <?php $imagePath = (string) ($article['image_path'] ?: $defaultImage); ?>
                    <img
                        src="<?= e(base_url($imagePath)) ?>"
                        alt="Illustration de l'article <?= e($article['title']) ?>"
                        loading="lazy"
                    >
                    <div class="card-body">
                        <p class="muted"><?= e((string) $article['category_name']) ?></p>
                        <h3>
                            <a href="<?= e(base_url('/article/' . $article['slug'])) ?>">
                                <?= e($article['title']) ?>
                            </a>
                        </h3>
                        <p><?= e($article['summary']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require BASE_PATH . '/views/partials/front_footer.php'; ?>
