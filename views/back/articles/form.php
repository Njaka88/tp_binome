<?php
$isEdit = $article !== null;

$titleValue = (string) old_input('title', $article['title'] ?? '');
$slugValue = (string) old_input('slug', $article['slug'] ?? '');
$summaryValue = (string) old_input('summary', $article['summary'] ?? '');
$contentValue = (string) old_input('content', $article['content'] ?? '');
$metaTitleValue = (string) old_input('meta_title', $article['meta_title'] ?? '');
$metaDescriptionValue = (string) old_input('meta_description', $article['meta_description'] ?? '');
$statusValue = (string) old_input('status', $article['status'] ?? 'draft');
$categoryValue = (int) old_input('category_id', (int) ($article['category_id'] ?? 0));
?>

<?php require BASE_PATH . '/views/partials/back_header.php'; ?>

<section>
    <h1><?= $isEdit ? 'Modifier article' : 'Nouvel article' ?></h1>

    <form method="post" action="<?= e(base_url($action)) ?>" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>

        <label for="title">Titre</label>
        <input id="title" name="title" type="text" required value="<?= e($titleValue) ?>">

        <label for="slug">Slug SEO (optionnel)</label>
        <input id="slug" name="slug" type="text" value="<?= e($slugValue) ?>" placeholder="ex: nouvelle-escalade-diplomatique">

        <label for="category_id">Categorie</label>
        <select id="category_id" name="category_id" required>
            <option value="">Selectionner...</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= e((string) $category['id']) ?>" <?= $categoryValue === (int) $category['id'] ? 'selected' : '' ?>>
                    <?= e($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="summary">Resume</label>
        <textarea id="summary" name="summary" rows="4" required><?= e($summaryValue) ?></textarea>

        <label for="content">Contenu</label>
        <textarea id="content" name="content" rows="12" required><?= e($contentValue) ?></textarea>

        <label for="image">Image (jpg, png, webp)</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
        <?php if ($isEdit && !empty($article['image_path'])): ?>
            <p class="muted">Image actuelle: <?= e($article['image_path']) ?></p>
        <?php endif; ?>

        <label for="meta_title">Meta title</label>
        <input id="meta_title" name="meta_title" type="text" value="<?= e($metaTitleValue) ?>" maxlength="60">

        <label for="meta_description">Meta description</label>
        <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"><?= e($metaDescriptionValue) ?></textarea>

        <label for="status">Statut</label>
        <select id="status" name="status">
            <option value="draft" <?= $statusValue === 'draft' ? 'selected' : '' ?>>Brouillon</option>
            <option value="published" <?= $statusValue === 'published' ? 'selected' : '' ?>>Publie</option>
        </select>

        <div class="actions-row">
            <button class="button" type="submit"><?= $isEdit ? 'Mettre a jour' : 'Creer' ?></button>
            <a class="button button-muted" href="<?= e(base_url('/admin/articles')) ?>">Annuler</a>
        </div>
    </form>
</section>

<?php require BASE_PATH . '/views/partials/back_footer.php'; ?>
