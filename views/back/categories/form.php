<?php
$isEdit = $category !== null;

$nameValue = (string) old_input('name', $category['name'] ?? '');
$slugValue = (string) old_input('slug', $category['slug'] ?? '');
$descriptionValue = (string) old_input('description', $category['description'] ?? '');
?>

<?php require BASE_PATH . '/views/partials/back_header.php'; ?>

<section>
    <h1><?= $isEdit ? 'Modifier categorie' : 'Nouvelle categorie' ?></h1>

    <form method="post" action="<?= e(base_url($action)) ?>" class="form-grid">
        <?= csrf_field() ?>

        <label for="name">Nom</label>
        <input id="name" name="name" type="text" required value="<?= e($nameValue) ?>">

        <label for="slug">Slug (optionnel)</label>
        <input id="slug" name="slug" type="text" value="<?= e($slugValue) ?>" placeholder="ex: tensions-regionales">

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"><?= e($descriptionValue) ?></textarea>

        <div class="actions-row">
            <button class="button" type="submit"><?= $isEdit ? 'Mettre a jour' : 'Creer' ?></button>
            <a class="button button-muted" href="<?= e(base_url('/admin/categories')) ?>">Annuler</a>
        </div>
    </form>
</section>

<?php require BASE_PATH . '/views/partials/back_footer.php'; ?>
