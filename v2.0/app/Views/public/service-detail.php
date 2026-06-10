<?php
$title = $category['name'] . ' - Zeta';
$theme = 'dark';
$bodyClass = 'public-page';
require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="container public-section">
    <div class="section-heading">
        <div>
            <p class="hero-kicker">Service Detail</p>
            <h1 class="section-title"><?= e($category['name']) ?></h1>
        </div>
        <p class="section-copy"><?= e($category['description'] ?? '') ?></p>
    </div>

    <div class="service-grid">
        <?php if (empty($subServices)): ?>
            <div class="empty-state">
                <h3>Belum ada sublayanan aktif.</h3>
                <p>Sub layanan untuk kategori ini belum tersedia.</p>
            </div>
        <?php endif; ?>
        <?php foreach ($subServices as $subService): ?>
            <article class="glass-card">
                <h2><?= e($subService['name']) ?></h2>
                <p><?= e($subService['description'] ?? 'Sublayanan konsultasi.') ?></p>
                <p class="price-tag">Rp <?= e(number_format((float) $subService['price'], 0, ',', '.')) ?></p>
                <a class="btn btn-primary" href="<?= e(url('/sub-services/' . $subService['id'])) ?>">Detail Sublayanan</a>
            </article>
        <?php endforeach; ?>
    </div>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
