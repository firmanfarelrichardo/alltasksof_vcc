<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Zeta') ?></title>
    <link rel="icon" type="image/png" href="<?= e(url('/assets/img/zeta-icon-192.png')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/theme.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/main.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/landing.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/forms.css')) ?>">
</head>
<body class="<?= e($bodyClass ?? 'public-page') ?>">
    <header class="public-header">
        <div class="container public-nav">
            <a class="brand public-brand" href="<?= e(url('/')) ?>" aria-label="Zeta">
                <img class="brand-icon" src="<?= e(url('/assets/img/zeta-icon-192.png')) ?>" alt="" aria-hidden="true">
                <span>Zeta</span>
            </a>

            <nav class="public-links" aria-label="Navigasi publik">
                <a href="<?= e(url('/')) ?>">Home</a>
                <a href="<?= e(url('/services')) ?>">Features</a>
                <a href="<?= e(url('/pricing')) ?>">Pricing</a>
                <a href="<?= e(url('/services')) ?>">Services</a>
                <a href="<?= e(url('/pricing')) ?>">FAQ</a>
            </nav>

            <div class="public-actions">
                <a class="public-login" href="<?= e(url('/login')) ?>">Login</a>
                <a class="btn btn-primary public-start" href="<?= e(url('/register')) ?>">Get started</a>
            </div>
        </div>
    </header>
