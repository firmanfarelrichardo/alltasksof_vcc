<?php
use App\Core\Session;

$title = 'Assignment Admin';
$success = Session::consumeFlash('success');
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Assignment Admin</h1>
    <p class="dashboard-subtitle"><?= e($admin['name']) ?> - <?= e($admin['email']) ?></p>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <form method="post" action="<?= e(url('/superadmin/admins/' . $admin['id'] . '/assignments')) ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="service_category_id">Layanan</label>
            <select class="form-control" id="service_category_id" name="service_category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= e($category['id']) ?>"><?= e($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-primary btn-dashboard" type="submit">Tambah Assignment</button>
    </form>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <h2>Assignment Saat Ini</h2>
    <?php if (empty($assignments)): ?>
        <p class="dashboard-subtitle">Admin belum memiliki assignment layanan.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Layanan</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?= e($assignment['service_name']) ?></td>
                            <td>
                                <form method="post" action="<?= e(url('/superadmin/admins/' . $admin['id'] . '/assignments/' . $assignment['id'])) ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-secondary btn-dashboard" type="submit">Hapus</button>
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
