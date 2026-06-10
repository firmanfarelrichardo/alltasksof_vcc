<?php
$role = \App\Core\Session::get('role');
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$menus = [
    'user' => [
        ['/user/dashboard', 'Dashboard'],
        ['/user/consultations', 'Konsultasi'],
    ],
    'admin' => [
        ['/admin/dashboard', 'Dashboard'],
        ['/admin/pipeline', 'Pipeline'],
        ['/admin/sub-services', 'Sublayanan'],
    ],
    'superadmin' => [
        ['/superadmin/dashboard', 'Dashboard'],
        ['/superadmin/users/pending', 'Approval Pengguna'],
        ['/superadmin/admins', 'Admin Assignment'],
        ['/superadmin/services', 'Layanan'],
        ['/superadmin/sub-services', 'Sublayanan'],
        ['/superadmin/consultations', 'Konsultasi'],
    ],
];
?>
<aside class="dashboard-sidebar">
    <a class="brand" href="<?= e(url('/')) ?>">
        <img class="brand-icon" src="<?= e(url('/assets/img/zeta-icon-192.png')) ?>" alt="" aria-hidden="true">
        <span>Zeta</span>
    </a>

    <p class="sidebar-section-label">Menu</p>
    <nav class="sidebar-nav" aria-label="Navigasi dashboard">
        <?php foreach ($menus[$role] ?? [] as [$href, $label]): ?>
            <a class="sidebar-link<?= str_ends_with($currentPath, $href) ? ' is-active' : '' ?>" href="<?= e(url($href)) ?>">
                <?= e($label) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <p class="sidebar-section-label">Workspace</p>
    <nav class="sidebar-nav" aria-label="Workspace">
        <a class="sidebar-link" href="<?= e(url('/services')) ?>">Service Catalog</a>
        <a class="sidebar-link" href="<?= e(url('/pricing')) ?>">Pricing</a>
        <a class="sidebar-link" href="<?= e(url('/')) ?>">Public Site</a>
    </nav>

    <div class="sidebar-footer">
        <div class="account-block">
            <strong><?= e(\App\Core\Session::get('user_name')) ?></strong><br>
            <span><?= e((string) $role) ?></span>
        </div>

        <form method="post" action="<?= e(url('/logout')) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-secondary btn-dashboard" type="submit">Logout</button>
        </form>
    </div>
</aside>
