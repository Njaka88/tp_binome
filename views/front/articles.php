<?php require BASE_PATH . '/views/partials/front_header.php'; ?>

<?php $defaultImage = '/assets/img/news-placeholder.svg'; ?>

<section>
    <h1>Tous les articles</h1>
    <p class="muted">Page <?= e((string) $page) ?> sur <?= e((string) $totalPages) ?></p>

    <?php if (empty($articles)): ?>
        <p>Aucun article publie.</p>
    <?php else: ?>
        <div class="grid cards">
            <?php foreach ($articles as $article): ?>
                <article class="card">
                    <?php $imagePath = (string) ($article['image_path'] ?: $defaultImage); ?>
                    <img
                        src="<?= e(base_url($imagePath)) ?>"
                        alt="Image article <?= e($article['title']) ?>"
                        loading="lazy"
                    >
                    <div class="card-body">
                        <p class="muted"><?= e((string) $article['category_name']) ?></p>
                        <h2>
                            <a href="<?= e(base_url('/article/' . $article['slug'])) ?>">
                                <?= e($article['title']) ?>
                            </a>
                        </h2>
                        <p><?= e($article['summary']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <nav class="pagination" aria-label="Pagination">
        <?php if ($page > 1): ?>
            <a href="<?= e(base_url('/articles?page=' . ($page - 1))) ?>">Precedent</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="<?= e(base_url('/articles?page=' . ($page + 1))) ?>">Suivant</a>
        <?php endif; ?>
    </nav>
</section>

<?php require BASE_PATH . '/views/partials/front_footer.php'; ?>
