<?php

use App\Core\Session;

$title = 'User Dashboard';
ob_start();
?>

<section class="dashboard-card dashboard-hero">
    <div>
        <h1 class="dashboard-title">User Dashboard</h1>
        <p class="dashboard-subtitle">Selamat datang, <?= e(Session::get('user_name')) ?>. Mulai konsultasi dari layanan yang tersedia.</p>
    </div>
    <div class="dashboard-actions">
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/services')) ?>">Pilih Layanan</a>
        <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/user/consultations')) ?>">Riwayat</a>
    </div>
</section>

<section class="dashboard-grid" style="margin-top: var(--space-4);">
    <div class="dashboard-card stat-card">
        <p class="stat-label">Status Akun</p>
        <p class="stat-value">OK</p>
        <?= status_badge((string) Session::get('status')) ?>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Konsultasi</p>
        <p class="stat-value">Flow</p>
        <p class="dashboard-subtitle">Lihat konsultasi Anda.</p>
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/user/consultations')) ?>">Riwayat Konsultasi</a>
    </div>
    <div class="dashboard-card stat-card">
        <p class="stat-label">Chat</p>
        <p class="stat-value">Paid</p>
        <p class="dashboard-subtitle">Chat aktif setelah pembayaran valid.</p>
    </div>
</section>

<section class="dashboard-panel">
    <div class="dashboard-card">
        <h2>Alur Cepat</h2>
        <ul class="quick-list">
            <li><span>Pilih sublayanan</span><?= status_badge('active') ?></li>
            <li><span>Bayar via Midtrans</span><?= status_badge('pending') ?></li>
            <li><span>Chat dengan admin</span><?= status_badge('paid') ?></li>
        </ul>
    </div>
    <div class="dashboard-card">
        <h2>Loading State</h2>
        <p class="dashboard-subtitle">Contoh state ringan saat data sedang dimuat.</p>
        <div class="loading-line" style="margin-top: var(--space-4);"></div>
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
