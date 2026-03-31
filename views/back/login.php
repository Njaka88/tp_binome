<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connexion administrateur pour la gestion des contenus.">
    <meta name="robots" content="noindex,nofollow">
    <title>Connexion BackOffice</title>
    <link rel="stylesheet" href="<?= e(base_url('/assets/css/app.css')) ?>">
</head>
<body class="theme-back login-page">
    <main class="login-wrap">
        <section class="login-card">
            <h1>Connexion BackOffice</h1>
            <p class="muted">Utilisez le compte administrateur par defaut fourni dans la documentation.</p>

            <?php if ($message = flash('success')): ?>
                <div class="alert alert-success"><?= e($message) ?></div>
            <?php endif; ?>
            <?php if ($message = flash('error')): ?>
                <div class="alert alert-error"><?= e($message) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= e(base_url('/admin/login')) ?>" class="form-grid">
                <?= csrf_field() ?>

                <label for="username">Username</label>
                <input id="username" name="username" type="text" required value="<?= e((string) old_input('username', '')) ?>">

                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>

                <button class="button" type="submit">Se connecter</button>
            </form>
        </section>
    </main>
</body>
</html>
