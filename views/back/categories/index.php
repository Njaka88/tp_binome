<?php require BASE_PATH . '/views/partials/back_header.php'; ?>

<section>
    <div class="section-head">
        <h1>Categories</h1>
        <a class="button" href="<?= e(base_url('/admin/categories/create')) ?>">Nouvelle categorie</a>
    </div>

    <?php if (empty($categories)): ?>
        <p>Aucune categorie.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= e((string) $category['id']) ?></td>
                        <td><?= e($category['name']) ?></td>
                        <td><?= e($category['slug']) ?></td>
                        <td><?= e((string) $category['description']) ?></td>
                        <td class="actions-cell">
                            <a class="button button-muted" href="<?= e(base_url('/admin/categories/' . $category['id'] . '/edit')) ?>">
                                Modifier
                            </a>
                            <form method="post" action="<?= e(base_url('/admin/categories/' . $category['id'] . '/delete')) ?>" onsubmit="return confirm('Supprimer cette categorie ?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="button button-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require BASE_PATH . '/views/partials/back_footer.php'; ?>
