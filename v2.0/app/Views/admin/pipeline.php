<?php
use App\Core\Session;

$title = 'Admin Pipeline';
$success = Session::consumeFlash('success');
$tabs = [
    'pending' => ['/admin/pipeline/payments/pending', 'Pending'],
    'cancelled' => ['/admin/pipeline/payments/cancelled', 'Cancelled'],
    'paid' => ['/admin/pipeline/payments/success', 'Paid'],
    'active' => ['/admin/pipeline/consultations/active', 'Active'],
    'closed' => ['/admin/pipeline/consultations/closed', 'Closed'],
];
$totalPages = max(1, (int) ceil($total / $limit));
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Admin Pipeline</h1>
    <p class="dashboard-subtitle">Pipeline konsultasi sesuai assignment layanan Anda.</p>
</section>
<section class="dashboard-grid" style="margin-top: var(--space-4);">
    <?php foreach (['pending', 'cancelled', 'paid', 'active', 'closed'] as $key): ?>
        <div class="dashboard-card">
            <h2><?= e(ucfirst($key)) ?></h2>
            <p><?= e((string) (int) ($summary[$key] ?? 0)) ?></p>
        </div>
    <?php endforeach; ?>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <nav class="sidebar-nav" style="grid-template-columns: repeat(5, minmax(0, 1fr)); margin-bottom: var(--space-4);">
        <?php foreach ($tabs as $key => [$href, $label]): ?>
            <a class="sidebar-link<?= $type === $key ? ' is-active' : '' ?>" href="<?= e(url($href)) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </nav>
    <form method="get" action="<?= e(url($tabs[$type][0])) ?>" class="form-group">
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
