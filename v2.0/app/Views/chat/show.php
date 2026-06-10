<?php
$title = 'Chat Konsultasi';
$status = (string) ($consultation['status'] ?? '');
$paymentStatus = (string) ($consultation['internal_status'] ?? '');
$readonlyReason = match (true) {
    !$canRead => 'Chat terkunci sampai pembayaran valid dan konsultasi aktif.',
    $status === 'closed' => 'Konsultasi sudah selesai. Chat hanya dapat dibaca.',
    default => '',
};
ob_start();
?>
<section class="dashboard-card">
    <h1 class="dashboard-title">Chat Konsultasi</h1>
    <p class="dashboard-subtitle"><?= e($consultation['sub_service_name']) ?> - <?= e($consultation['category_name']) ?></p>
</section>

<section
    class="dashboard-card chat-panel"
    style="margin-top: var(--space-4);"
    data-chat
    data-messages-url="<?= e(url($messagesUrl)) ?>"
    data-csrf-token="<?= e(csrf_token()) ?>"
    data-can-send="<?= $canSend ? '1' : '0' ?>"
    data-current-user-id="<?= e(\App\Core\Session::get('user_id')) ?>"
>
    <div class="dashboard-grid">
        <div>
            <h2>Status Konsultasi</h2>
            <?= status_badge($status) ?>
        </div>
        <div>
            <h2>Status Payment</h2>
            <?= status_badge($paymentStatus ?: null) ?>
        </div>
        <div>
            <h2>Akses</h2>
            <?= status_badge($canSend ? 'active' : ($canRead ? 'closed' : 'pending')) ?>
        </div>
    </div>

    <?php if ($readonlyReason !== ''): ?>
        <p class="flash flash-error" style="margin-top: var(--space-4);"><?= e($readonlyReason) ?></p>
    <?php endif; ?>

    <div class="chat-messages" data-chat-messages aria-live="polite">
        <p class="dashboard-subtitle" data-chat-empty>Memuat pesan...</p>
    </div>

    <form class="chat-form chat-composer" data-chat-form style="margin-top: var(--space-4);">
        <div class="form-group">
            <label for="chat-message">Pesan</label>
            <textarea
                class="form-control"
                id="chat-message"
                name="message"
                rows="4"
                maxlength="3000"
                placeholder="Tulis pesan konsultasi"
                <?= $canSend ? '' : 'disabled' ?>
            ></textarea>
        </div>
        <button class="btn btn-primary btn-dashboard" type="submit" <?= $canSend ? '' : 'disabled' ?>>Kirim</button>
        <p class="dashboard-subtitle" data-chat-status></p>
    </form>
</section>

<script src="<?= e(url('/assets/js/chat-polling.js')) ?>"></script>
<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/dashboard-layout.php';
?>
