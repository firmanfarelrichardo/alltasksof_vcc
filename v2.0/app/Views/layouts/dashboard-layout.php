<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Dashboard') ?></title>
    <link rel="icon" type="image/png" href="<?= e(url('/assets/img/zeta-icon-192.png')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/theme.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/main.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/forms.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('/assets/css/dashboard.css')) ?>">
</head>
<body class="dashboard-page">
    <div class="dashboard-window">
        <div class="dashboard-browser-bar" aria-hidden="true">
            <span></span><span></span><span></span>
            <div class="dashboard-browser-address">https://app.zeta.local</div>
        </div>

        <div class="dashboard-shell">
            <?php require BASE_PATH . '/app/Views/layouts/dashboard-sidebar.php'; ?>

            <div class="dashboard-main">
                <?php require BASE_PATH . '/app/Views/layouts/dashboard-topbar.php'; ?>

                <main class="dashboard-content">
                    <?= $content ?? '' ?>
                </main>
            </div>
        </div>
    </div>

    <div class="mobile-sidebar-backdrop" data-sidebar-close></div>

    <script src="<?= e(url('/assets/js/sidebar.js')) ?>"></script>
</body>
</html>
