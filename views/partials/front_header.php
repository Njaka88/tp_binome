<?php

$pageTitle = trim((string) ($metaTitle ?? app_config('seo.default_title')));
$siteName = (string) app_config('app.name', 'Iran News Portal');
$description = trim((string) ($metaDescription ?? app_config('seo.default_description')));
$canonical = $canonicalUrl ?? null;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($description) ?>">
    <meta name="robots" content="index,follow">
    <title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
    <?php if ($canonical !== null): ?>
        <link rel="canonical" href="<?= e((string) $canonical) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= e(base_url('/assets/css/app.css')) ?>">
</head>
<body class="theme-front">
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?= e(base_url('/')) ?>">Iran News Portal</a>
            <nav class="main-nav" aria-label="Navigation principale">
                <a href="<?= e(base_url('/')) ?>">Accueil</a>
                <a href="<?= e(base_url('/articles')) ?>">Articles</a>
                <a href="<?= e(base_url('/admin/login')) ?>">BackOffice</a>
            </nav>
        </div>
    </header>
    <main class="container page-content">
        <?php if ($message = flash('success')): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
        <?php endif; ?>
        <?php if ($message = flash('error')): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>
