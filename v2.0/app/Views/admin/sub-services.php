<?php
use App\Core\Session;

$title = 'Sublayanan Admin';
$success = Session::consumeFlash('success');
$error = Session::consumeFlash('error');
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Sublayanan</h1>
    <p class="dashboard-subtitle">Daftar sub layanan sesuai assignment Anda.</p>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="flash flash-error"><?= e($error) ?></p><?php endif; ?>
    <?php if (empty($subServices)): ?>
        <p class="dashboard-subtitle">Belum ada sub layanan pada assignment Anda.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($subServices as $subService): ?>
                        <tr>
                            <td><?= e($subService['name']) ?></td>
                            <td><?= e($subService['category_name']) ?></td>
                            <td>Rp <?= e(number_format((float) $subService['price'], 0, ',', '.')) ?></td>
                            <td><?= ((int) $subService['is_active'] === 1) ? 'Aktif' : 'Nonaktif' ?></td>
                            <td><a class="btn btn-primary btn-dashboard" href="<?= e(url('/admin/sub-services/' . $subService['id'] . '/edit')) ?>">Edit Harga</a></td>
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
