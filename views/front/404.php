<?php require BASE_PATH . '/views/partials/front_header.php'; ?>

<section class="empty-state">
    <h1>404 - Page introuvable</h1>
    <p>La ressource demandee n'existe pas ou a ete deplacee.</p>
    <a class="button" href="<?= e(base_url('/')) ?>">Revenir a l'accueil</a>
</section>

<?php require BASE_PATH . '/views/partials/front_footer.php'; ?>
