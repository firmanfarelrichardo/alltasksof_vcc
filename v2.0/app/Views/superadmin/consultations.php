<?php
$title = 'Semua Konsultasi';
$tabs = ['pending', 'cancelled', 'paid', 'active', 'closed'];
$totalPages = max(1, (int) ceil($total / $limit));
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Semua Konsultasi</h1>
    <p class="dashboard-subtitle">Superadmin melihat seluruh pipeline konsultasi.</p>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <nav class="sidebar-nav" style="grid-template-columns: repeat(5, minmax(0, 1fr)); margin-bottom: var(--space-4);">
        <?php foreach ($tabs as $tab): ?>
            <a class="sidebar-link<?= $type === $tab ? ' is-active' : '' ?>" href="<?= e(url('/superadmin/consultations?tab=' . $tab)) ?>"><?= e(ucfirst($tab)) ?></a>
        <?php endforeach; ?>
    </nav>
    <form method="get" action="<?= e(url('/superadmin/consultations')) ?>" class="form-group">
        <input type="hidden" name="tab" value="<?= e($type) ?>">
        <label for="q">Filter</label>
        <input class="form-control" id="q" name="q" value="<?= e($search) ?>" placeholder="Nama user, email, layanan">
        <button class="btn btn-secondary btn-dashboard" type="submit">Filter</button>
    </form>
    <?php require BASE_PATH . '/app/Views/layouts/pipeline-table.php'; ?>
</section>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
