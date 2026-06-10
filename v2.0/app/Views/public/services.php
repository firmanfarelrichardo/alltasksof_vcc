<?php
$title = 'Layanan - Zeta';
$theme = 'dark';
$bodyClass = 'public-page';
require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="container public-section">
    <div class="section-heading">
        <div>
            <p class="hero-kicker">Service Catalog</p>
            <h1 class="section-title">Layanan Konsultasi</h1>
        </div>
        <p class="section-copy">Tiga domain konsultasi utama untuk membantu menentukan keputusan arsitektur yang lebih tepat.</p>
    </div>
    <div class="service-grid">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <h3>Belum ada layanan aktif.</h3>
                <p>Silakan kembali lagi setelah superadmin mengaktifkan katalog layanan.</p>
            </div>
        <?php endif; ?>
        <?php foreach ($categories as $index => $category): ?>
            <article class="glass-card">
                <p class="service-card-meta"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?> / Category</p>
                <h2><?= e($category['name']) ?></h2>
                <p><?= e($category['description'] ?? 'Layanan konsultasi teknologi.') ?></p>
                <p class="price-tag">Mulai dari Rp <?= e(number_format((float) ($category['min_price'] ?? 0), 0, ',', '.')) ?></p>
                <a class="btn btn-primary" href="<?= e(url('/services/' . $category['id'])) ?>">Lihat Detail</a>
            </article>
        <?php endforeach; ?>
    </div>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
