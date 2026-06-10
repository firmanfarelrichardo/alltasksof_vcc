<?php

use App\Core\Session;

$title = 'Admin Dashboard';
ob_start();
?>

<section class="dashboard-card dashboard-hero">
    <div>
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <p class="dashboard-subtitle">Selamat datang, <?= e(Session::get('user_name')) ?>. Pantau konsultasi sesuai assignment layanan.</p>
    </div>
    <div class="dashboard-actions">
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/admin/pipeline')) ?>">Buka Pipeline</a>
        <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/admin/sub-services')) ?>">Sublayanan</a>
    </div>
</section>

<section class="dashboard-grid" style="margin-top: var(--space-4);">
    <div class="dashboard-card stat-card">
        <p class="stat-label">Konsultasi Masuk</p>
        <p class="stat-value">Pipeline</p>
        <p class="dashboard-subtitle">Pantau pending payment dan konsultasi aktif.</p>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Sublayanan</p>
        <p class="stat-value">Scoped</p>
        <p class="dashboard-subtitle">Lihat sub layanan sesuai assignment Anda.</p>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Konsultasi Aktif</p>
        <p class="stat-value">Chat</p>
        <p class="dashboard-subtitle">Balas konsultasi yang sudah paid.</p>
    </div>
</section>

<section class="dashboard-panel">
    <div class="dashboard-card">
        <h2>Prioritas Hari Ini</h2>
        <ul class="quick-list">
            <li><span>Payment pending</span><?= status_badge('pending') ?></li>
            <li><span>Konsultasi aktif</span><?= status_badge('active') ?></li>
            <li><span>Konsultasi selesai</span><?= status_badge('closed') ?></li>
        </ul>
    </div>
    <div class="dashboard-card">
        <h2>Empty State</h2>
        <div class="empty-state">
            <h3>Tidak ada alert baru.</h3>
            <p>Pipeline akan menampilkan data sesuai assignment Anda.</p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
