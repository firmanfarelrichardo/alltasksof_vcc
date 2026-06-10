<?php
$title = 'Pricing - Zeta';
$theme = 'dark';
$bodyClass = 'public-page';
require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="container public-section">
    <div class="section-heading">
        <div>
            <p class="hero-kicker">Transparent Pricing</p>
            <h1 class="section-title">Harga Konsultasi</h1>
        </div>
        <p class="section-copy">Harga dibaca dari database dan menjadi snapshot saat pengguna membuat transaksi pembayaran.</p>
    </div>
    <div class="service-grid">
        <?php if (empty($subServices)): ?>
            <div class="empty-state">
                <h3>Belum ada harga aktif.</h3>
                <p>Harga akan tampil setelah sublayanan aktif tersedia.</p>
            </div>
        <?php endif; ?>
        <?php foreach ($subServices as $subService): ?>
            <article class="glass-card">
                <p class="hero-kicker"><?= e($subService['category_name']) ?></p>
                <h2><?= e($subService['name']) ?></h2>
                <p class="price-tag">Rp <?= e(number_format((float) $subService['price'], 0, ',', '.')) ?></p>
                <a class="btn btn-primary" href="<?= e(url('/sub-services/' . $subService['id'])) ?>">Lihat Detail</a>
            </article>
        <?php endforeach; ?>
    </div>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
