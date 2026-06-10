<?php
use App\Core\Session;

$isEdit = $category !== null;
$title = $isEdit ? 'Edit Layanan' : 'Tambah Layanan';
$error = Session::consumeFlash('error');
ob_start();
?>

<section class="dashboard-card">
    <h1 class="dashboard-title"><?= e($title) ?></h1>
    <?php if ($error): ?><p class="flash flash-error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="<?= e(url($isEdit ? '/superadmin/services/' . $category['id'] : '/superadmin/services')) ?>">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?><input type="hidden" name="_method" value="PATCH"><?php endif; ?>

        <div class="form-group">
            <label for="name">Nama</label>
            <input class="form-control" id="name" name="name" value="<?= e($category['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input class="form-control" id="slug" name="slug" value="<?= e($category['slug'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <input class="form-control" id="description" name="description" value="<?= e($category['description'] ?? '') ?>">
        </div>
        <label><input type="checkbox" name="is_active" value="1" <?= !$isEdit || (int) $category['is_active'] === 1 ? 'checked' : '' ?>> Aktif</label>
        <p><button class="btn btn-primary btn-dashboard" type="submit">Simpan</button></p>
    </form>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
