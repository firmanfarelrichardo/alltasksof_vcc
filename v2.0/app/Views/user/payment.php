<?php
use App\Core\Session;

$title = 'Pembayaran Midtrans';
$success = Session::consumeFlash('success');
$error = Session::consumeFlash('error');
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Pembayaran Midtrans Sandbox</h1>
    <p class="dashboard-subtitle"><?= e($consultation['sub_service_name']) ?> - <?= e($consultation['category_name']) ?></p>
</section>
<section class="dashboard-card" style="margin-top: var(--space-4);">
    <?php if ($success): ?><p class="flash flash-success"><?= e($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="flash flash-error"><?= e($error) ?></p><?php endif; ?>
    <p>Order ID: <?= e($payment['order_id'] ?? '-') ?></p>
    <p>Amount: Rp <?= e(number_format((float) ($payment['amount'] ?? $consultation['current_price'] ?? 0), 0, ',', '.')) ?></p>
    <p>Status Payment: <?= status_badge($payment['internal_status'] ?? 'pending') ?></p>
    <p>Status Konsultasi: <?= status_badge($consultation['status'] ?? null) ?></p>

    <?php if ($payment === null && $consultation['status'] === 'waiting_payment'): ?>
        <form method="post" action="<?= e(url('/user/consultations/' . $consultation['id'] . '/payment')) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-primary btn-dashboard" type="submit">Buat Pembayaran Midtrans</button>
        </form>
    <?php elseif (($payment['internal_status'] ?? '') === 'pending'): ?>
        <?php if (!empty($payment['snap_token']) && $midtransClientKey !== ''): ?>
            <button class="btn btn-primary btn-dashboard" id="pay-button" type="button">Bayar dengan Snap</button>
        <?php else: ?>
            <p class="dashboard-subtitle">Snap belum siap. Pastikan Sandbox Server Key dan Client Key sudah diisi pada `.env`.</p>
        <?php endif; ?>
        <form method="post" action="<?= e(url('/user/payments/' . $payment['id'] . '/refresh-status')) ?>" style="display:inline">
            <?= csrf_field() ?>
            <button class="btn btn-secondary btn-dashboard" type="submit">Refresh Status Backend</button>
        </form>
    <?php elseif (($payment['internal_status'] ?? '') === 'paid'): ?>
        <a class="btn btn-primary btn-dashboard" href="<?= e(url('/user/consultations/' . $consultation['id'])) ?>">Lihat Konsultasi</a>
    <?php else: ?>
        <p class="dashboard-subtitle">Pembayaran tidak aktif. Buat konsultasi baru jika perlu mengulang pembayaran.</p>
    <?php endif; ?>
</section>
<?php if (($payment['internal_status'] ?? '') === 'pending' && !empty($payment['snap_token']) && $midtransClientKey !== ''): ?>
    <script src="<?= e($snapScriptUrl) ?>" data-client-key="<?= e($midtransClientKey) ?>"></script>
    <script>
        document.getElementById('pay-button')?.addEventListener('click', function () {
            window.snap.pay('<?= e($payment['snap_token']) ?>', {
                onSuccess: function () {
                    window.location.href = '<?= e(url('/user/payments/' . $payment['id'])) ?>';
                },
                onPending: function () {
                    window.location.href = '<?= e(url('/user/payments/' . $payment['id'])) ?>';
                },
                onError: function () {
                    window.location.href = '<?= e(url('/user/payments/' . $payment['id'])) ?>';
                },
                onClose: function () {
                    window.location.href = '<?= e(url('/user/payments/' . $payment['id'])) ?>';
                }
            });
        });
    </script>
<?php endif; ?>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
