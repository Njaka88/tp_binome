<?php require BASE_PATH . '/views/partials/back_header.php'; ?>

<section>
    <h1>Dashboard</h1>
    <p>Bienvenue <?= e((string) ($user['full_name'] ?? $user['username'] ?? 'Admin')) ?>.</p>

    <div class="grid stats">
        <article class="stat-card">
            <h2>Articles</h2>
            <p class="big-number"><?= e((string) $articlesCount) ?></p>
            <a href="<?= e(base_url('/admin/articles')) ?>">Gerer les articles</a>
        </article>
        <article class="stat-card">
            <h2>Categories</h2>
            <p class="big-number"><?= e((string) $categoriesCount) ?></p>
            <a href="<?= e(base_url('/admin/categories')) ?>">Gerer les categories</a>
        </article>
    </div>
</section>

<?php require BASE_PATH . '/views/partials/back_footer.php'; ?>
