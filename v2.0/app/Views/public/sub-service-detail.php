<?php
use App\Core\Session;

$title = $subService['name'] . ' - Zeta';
$theme = 'dark';
$bodyClass = 'public-page';
$canConsult = Session::get('role') === 'user' && Session::get('status') === 'approved';
require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="container public-section">
    <article class="glass-card subservice-hero">
        <p class="hero-kicker"><?= e($subService['category_name']) ?></p>
        <h1 class="section-title"><?= e($subService['name']) ?></h1>
        <p><?= e($subService['description'] ?? 'Sublayanan konsultasi teknologi.') ?></p>
        <h2 class="price-tag">Rp <?= e(number_format((float) $subService['price'], 0, ',', '.')) ?></h2>
        <p>Chat konsultasi aktif setelah pembayaran valid. Status akhir pembayaran hanya diproses oleh backend.</p>
        <?php if ($canConsult): ?>
            <form method="post" action="<?= e(url('/user/consultations')) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="sub_service_id" value="<?= e($subService['id']) ?>">
                <button class="btn btn-primary" type="submit">Pilih Konsultasi</button>
            </form>
        <?php else: ?>
            <a class="btn btn-primary" href="<?= e(url('/register')) ?>">Register untuk Konsultasi</a>
        <?php endif; ?>
    </article>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
