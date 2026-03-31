<?php

$pageTitle = trim((string) ($metaTitle ?? 'BackOffice'));
$description = trim((string) ($metaDescription ?? 'Administration des contenus'));
$siteName = (string) app_config('app.name', 'Iran News Portal');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($description) ?>">
    <meta name="robots" content="noindex,nofollow">
    <title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
    <link rel="stylesheet" href="<?= e(base_url('/assets/css/app.css')) ?>">
</head>
<body class="theme-back">
    <header class="admin-header">
        <div class="container admin-header-inner">
            <a class="brand" href="<?= e(base_url('/admin')) ?>">BackOffice</a>
            <nav class="admin-nav" aria-label="Navigation admin">
                <a href="<?= e(base_url('/admin')) ?>">Dashboard</a>
                <a href="<?= e(base_url('/admin/articles')) ?>">Articles</a>
                <a href="<?= e(base_url('/admin/categories')) ?>">Categories</a>
                <a href="<?= e(base_url('/')) ?>" target="_blank" rel="noopener">Voir FO</a>
            </nav>
            <form method="post" action="<?= e(base_url('/admin/logout')) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="button button-muted">Deconnexion</button>
            </form>
        </div>
    </header>
    <main class="container page-content">
        <?php if ($message = flash('success')): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
        <?php endif; ?>
        <?php if ($message = flash('error')): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>
