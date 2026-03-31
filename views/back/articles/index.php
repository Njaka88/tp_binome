<?php require BASE_PATH . '/views/partials/back_header.php'; ?>

<section>
    <div class="section-head">
        <h1>Articles</h1>
        <a class="button" href="<?= e(base_url('/admin/articles/create')) ?>">Nouvel article</a>
    </div>

    <?php if (empty($articles)): ?>
        <p>Aucun article.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Categorie</th>
                    <th>Slug</th>
                    <th>Statut</th>
                    <th>Publication</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= e((string) $article['id']) ?></td>
                        <td><?= e($article['title']) ?></td>
                        <td><?= e((string) $article['category_name']) ?></td>
                        <td><?= e($article['slug']) ?></td>
                        <td>
                            <span class="badge <?= $article['status'] === 'published' ? 'badge-ok' : 'badge-muted' ?>">
                                <?= e($article['status']) ?>
                            </span>
                        </td>
                        <td><?= e((string) ($article['published_at'] ?? '-')) ?></td>
                        <td class="actions-cell">
                            <a class="button button-muted" href="<?= e(base_url('/admin/articles/' . $article['id'] . '/edit')) ?>">
                                Modifier
                            </a>
                            <form method="post" action="<?= e(base_url('/admin/articles/' . $article['id'] . '/delete')) ?>" onsubmit="return confirm('Supprimer cet article ?');">
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
