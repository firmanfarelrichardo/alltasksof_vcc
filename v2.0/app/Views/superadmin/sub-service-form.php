<?php
use App\Core\Session;

$isEdit = $subService !== null;
$title = $isEdit ? 'Edit Sublayanan' : 'Tambah Sublayanan';
$error = Session::consumeFlash('error');
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title"><?= e($title) ?></h1>
    <?php if ($error): ?><p class="flash flash-error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="<?= e(url($isEdit ? '/superadmin/sub-services/' . $subService['id'] : '/superadmin/sub-services')) ?>">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?><input type="hidden" name="_method" value="PATCH"><?php endif; ?>

        <div class="form-group">
            <label for="service_category_id">Kategori</label>
            <select class="form-control" id="service_category_id" name="service_category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= e($category['id']) ?>" <?= $isEdit && (int) $subService['service_category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                        <?= e($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="name">Nama</label>
            <input class="form-control" id="name" name="name" value="<?= e($subService['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input class="form-control" id="slug" name="slug" value="<?= e($subService['slug'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <input class="form-control" id="description" name="description" value="<?= e($subService['description'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="price">Harga</label>
            <input class="form-control" id="price" name="price" type="number" min="0" step="1000" value="<?= e($subService['price'] ?? '0') ?>" required>
        </div>
        <label><input type="checkbox" name="is_active" value="1" <?= !$isEdit || (int) $subService['is_active'] === 1 ? 'checked' : '' ?>> Aktif</label>
        <p><button class="btn btn-primary btn-dashboard" type="submit">Simpan</button></p>
    </form>
</section>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
