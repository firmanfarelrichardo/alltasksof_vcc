<?php

use App\Core\Session;

$title = 'Superadmin Dashboard';
ob_start();
?>

<section class="dashboard-card dashboard-hero">
    <div>
        <h1 class="dashboard-title">Superadmin Dashboard</h1>
        <p class="dashboard-subtitle">Selamat datang, <?= e(Session::get('user_name')) ?>. Kelola approval, layanan, admin, dan pipeline sistem.</p>
    </div>
    <div class="dashboard-actions">
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/superadmin/users/pending')) ?>">Approval</a>
        <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/superadmin/consultations')) ?>">Pipeline</a>
    </div>
</section>

<section class="dashboard-grid" style="margin-top: var(--space-4);">
    <div class="dashboard-card stat-card">
        <p class="stat-label">Approval Pengguna</p>
        <p class="stat-value">Review</p>
        <p class="dashboard-subtitle">Kelola user yang menunggu persetujuan.</p>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Admin</p>
        <p class="stat-value">Scope</p>
        <p class="dashboard-subtitle">Atur assignment admin ke layanan.</p>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Layanan</p>
        <p class="stat-value">Catalog</p>
        <p class="dashboard-subtitle">Kelola kategori dan sub layanan.</p>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Konsultasi</p>
        <p class="stat-value">All</p>
        <p class="dashboard-subtitle">Lihat seluruh pipeline konsultasi.</p>
    </div>
</section>

<section class="dashboard-panel">
    <div class="dashboard-card">
        <h2>Kontrol Sistem</h2>
        <ul class="quick-list">
            <li><span>Approval user baru</span><?= status_badge('pending') ?></li>
            <li><span>Assignment admin</span><?= status_badge('approved') ?></li>
            <li><span>Katalog layanan</span><?= status_badge('active') ?></li>
        </ul>
    </div>
    <div class="dashboard-card">
        <h2>Loading State</h2>
        <p class="dashboard-subtitle">Skeleton ringan untuk proses baca data.</p>
        <div class="loading-line" style="margin-top: var(--space-4);"></div>
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
