<?php
$title = 'Admin Assignment';
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Admin Assignment</h1>
    <p class="dashboard-subtitle">Pilih admin untuk mengatur assignment layanan.</p>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nama</th><th>Email</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= e($admin['name']) ?></td>
                        <td><?= e($admin['email']) ?></td>
                        <td><a class="btn btn-primary btn-dashboard" href="<?= e(url('/superadmin/admins/' . $admin['id'] . '/assignments')) ?>">Assignment</a></td>
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
