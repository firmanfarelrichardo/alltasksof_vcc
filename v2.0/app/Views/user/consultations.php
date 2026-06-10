<?php
use App\Core\Session;

$title = 'Riwayat Konsultasi';
$success = Session::consumeFlash('success');
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Riwayat Konsultasi</h1>
    <p class="dashboard-subtitle">Daftar konsultasi dan status pembayaran Anda.</p>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <?php if (empty($consultations)): ?>
        <div class="empty-state">
            <h3>Belum ada konsultasi.</h3>
            <p>Pilih layanan untuk memulai alur konsultasi dan pembayaran.</p>
            <a class="btn btn-primary btn-dashboard" href="<?= e(url('/services')) ?>">Pilih Layanan</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Sublayanan</th><th>Kategori</th><th>Konsultasi</th><th>Payment</th><th>Harga</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($consultations as $consultation): ?>
                        <tr>
                            <td><?= e($consultation['sub_service_name']) ?></td>
                            <td><?= e($consultation['category_name']) ?></td>
                            <td><?= status_badge($consultation['status'] ?? null) ?></td>
                            <td><?= status_badge($consultation['internal_status'] ?? null) ?></td>
                            <td>Rp <?= e(number_format((float) ($consultation['amount'] ?? 0), 0, ',', '.')) ?></td>
                            <td><a class="btn btn-primary btn-dashboard" href="<?= e(url('/user/consultations/' . $consultation['id'])) ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
