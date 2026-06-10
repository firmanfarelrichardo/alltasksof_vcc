<?php if (empty($items)): ?>
    <div class="empty-state">
        <h3>Belum ada data pada tab ini.</h3>
        <p>Data akan muncul sesuai status pipeline dan scope assignment admin.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Layanan</th>
                    <th>Konsultasi</th>
                    <th>Payment</th>
                    <th>Order</th>
                    <th>Amount</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['user_name']) ?><br><span class="dashboard-subtitle"><?= e($item['user_email']) ?></span></td>
                        <td><?= e($item['sub_service_name']) ?><br><span class="dashboard-subtitle"><?= e($item['category_name']) ?></span></td>
                        <td><?= status_badge($item['status'] ?? null) ?></td>
                        <td><?= status_badge($item['internal_status'] ?? null) ?><br><span class="dashboard-subtitle"><?= e($item['transaction_status'] ?? '-') ?></span></td>
                        <td><?= e($item['order_id'] ?? '-') ?></td>
                        <td>Rp <?= e(number_format((float) ($item['amount'] ?? 0), 0, ',', '.')) ?></td>
                        <td>
                            <?php if (($item['status'] ?? '') === 'active' && \App\Core\Session::get('role') === 'admin'): ?>
                                <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/admin/consultations/' . $item['id'] . '/chat')) ?>">Chat</a>
                                <form method="post" action="<?= e(url('/admin/consultations/' . $item['id'] . '/close')) ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button class="btn btn-primary btn-dashboard" type="submit">Close</button>
                                </form>
                            <?php elseif (($item['status'] ?? '') === 'closed' && \App\Core\Session::get('role') === 'admin'): ?>
                                <a class="btn btn-secondary btn-dashboard" href="<?= e(url('/admin/consultations/' . $item['id'] . '/chat')) ?>">Riwayat Chat</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($totalPages > 1): ?>
    <div style="display:flex; gap: var(--space-2); margin-top: var(--space-4);">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php $query = http_build_query(['tab' => $type ?? 'pending', 'page' => $i, 'q' => $search ?? '']); ?>
            <a class="btn btn-secondary btn-dashboard" href="?<?= e($query) ?>"><?= e($i) ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
