<?php
use App\Core\Session;

$title = 'Manajemen Layanan';
$success = Session::consumeFlash('success');
$error = Session::consumeFlash('error');
ob_start();
?>

<section class="dashboard-card">
    <h1 class="dashboard-title">Manajemen Layanan</h1>
    <p class="dashboard-subtitle">Kelola kategori layanan konsultasi.</p>
    <a class="btn btn-primary btn-dashboard" href="<?= e(url('/superadmin/services/create')) ?>">Tambah Layanan</a>
</section>

<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="flash flash-error"><?= e($error) ?></p><?php endif; ?>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nama</th><th>Slug</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= e($category['name']) ?></td>
                        <td><?= e($category['slug']) ?></td>
                        <td><?= ((int) $category['is_active'] === 1) ? 'Aktif' : 'Nonaktif' ?></td>
                        <td>
                            <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/superadmin/services/' . $category['id'] . '/edit')) ?>">Edit</a>
                            <form method="post" action="<?= e(url('/superadmin/services/' . $category['id'])) ?>" style="display:inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-secondary btn-dashboard" type="submit">Disable</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
