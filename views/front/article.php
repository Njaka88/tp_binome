<?php require BASE_PATH . '/views/partials/front_header.php'; ?>

<?php $imagePath = (string) ($article['image_path'] ?: '/assets/img/news-placeholder.svg'); ?>

<article class="article-detail">
    <p class="muted">
        Categorie:
        <a href="<?= e(base_url('/categorie/' . $article['category_slug'])) ?>"><?= e((string) $article['category_name']) ?></a>
    </p>
    <h1><?= e($article['title']) ?></h1>
    <p class="muted">Publie le <?= e((string) $article['published_at']) ?></p>

    <img
        class="hero-image"
        src="<?= e(base_url($imagePath)) ?>"
        alt="Illustration principale de <?= e($article['title']) ?>"
    >

    <h2>Resume</h2>
    <p><?= e($article['summary']) ?></p>

    <h2>Analyse complete</h2>
    <div class="article-content"><?= nl2br(e($article['content'])) ?></div>
</article>

<?php require BASE_PATH . '/views/partials/front_footer.php'; ?>
