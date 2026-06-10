<?php
use App\Core\Session;

$title = 'Detail Konsultasi';
$success = Session::consumeFlash('success');
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Detail Konsultasi</h1>
    <p class="dashboard-subtitle"><?= e($consultation['sub_service_name']) ?> - <?= e($consultation['category_name']) ?></p>
</section>
<section class="dashboard-grid" style="margin-top: var(--space-4);">
    <div class="dashboard-card">
        <h2>Status Konsultasi</h2>
        <?= status_badge($consultation['status'] ?? null) ?>
    </div>
    <div class="dashboard-card">
        <h2>Status Payment</h2>
        <?= status_badge($consultation['internal_status'] ?? null) ?>
    </div>
    <div class="dashboard-card">
        <h2>Harga Snapshot</h2>
        <p>Rp <?= e(number_format((float) ($consultation['amount'] ?? 0), 0, ',', '.')) ?></p>
    </div>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <?php if (($consultation['internal_status'] ?? '') === 'pending'): ?>
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/user/consultations/' . $consultation['id'] . '/payment')) ?>">Lanjut Pembayaran</a>
    <?php elseif (($consultation['status'] ?? '') === 'active'): ?>
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/user/consultations/' . $consultation['id'] . '/chat')) ?>">Buka Chat</a>
    <?php elseif (($consultation['status'] ?? '') === 'closed'): ?>
        <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/user/consultations/' . $consultation['id'] . '/chat')) ?>">Lihat Riwayat Chat</a>
    <?php else: ?>
        <p class="dashboard-subtitle">Konsultasi tidak aktif.</p>
    <?php endif; ?>
</section>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
