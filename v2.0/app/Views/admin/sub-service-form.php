<?php
$title = 'Edit Harga Sublayanan';
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Edit Harga Sublayanan</h1>
    <p class="dashboard-subtitle"><?= e($subService['name']) ?> - <?= e($subService['category_name']) ?></p>
    <form method="post" action="<?= e(url('/admin/sub-services/' . $subService['id'])) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PATCH">
        <div class="form-group">
            <label for="price">Harga</label>
            <input class="form-control" id="price" name="price" type="number" min="0" step="1000" value="<?= e($subService['price']) ?>" required>
        </div>
        <button class="btn btn-primary btn-dashboard" type="submit">Simpan Harga</button>
    </form>
</section>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
