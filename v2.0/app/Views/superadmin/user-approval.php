<?php

use App\Core\Session;

$title = 'Approval User Pending';
$success = Session::consumeFlash('success');
ob_start();
?>

<section class="dashboard-card">
    <h1 class="dashboard-title">Approval User Pending</h1>
    <p class="dashboard-subtitle">Setujui atau tolak akun user baru.</p>
</section>

<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?>
        <p class="flash flash-success" role="status"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <div class="empty-state">
            <h3>Belum ada pengguna pending.</h3>
            <p>Daftar ini akan terisi saat user baru selesai registrasi dan menunggu keputusan superadmin.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= e($user['name']) ?></td>
                            <td><?= e($user['email']) ?></td>
                            <td><?= status_badge($user['status'] ?? null) ?></td>
                            <td><?= e($user['created_at']) ?></td>
                            <td>
                                <form method="post" action="<?= e(url('/superadmin/users/' . $user['id'] . '/approve')) ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button class="btn btn-primary btn-dashboard" type="submit">Approve</button>
                                </form>

                                <form method="post" action="<?= e(url('/superadmin/users/' . $user['id'] . '/reject')) ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button class="btn btn-secondary btn-dashboard" type="submit">Reject</button>
                                </form>
                            </td>
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
