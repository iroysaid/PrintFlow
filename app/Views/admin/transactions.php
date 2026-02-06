<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <h2 class="text-white mb-4">Transaction History</h2>
        
        <div class="glass-panel p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $t): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?= $t['id'] ?></td>
                            <td><?= $t['no_invoice'] ?></td>
                            <td><?= date('d M Y H:i', strtotime($t['tgl_masuk'])) ?></td>
                            <td>
                                <?= htmlspecialchars($t['customer_name'] ?? 'Guest') ?>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($t['customer_phone'] ?? '-') ?></small>
                            </td>
                            <td class="text-warning fw-bold">Rp <?= number_format($t['grand_total'], 0, ',', '.') ?></td>
                            <td class="text-end pe-4">
                                <a href="/pos/printInvoice/<?= $t['id'] ?>" target="_blank" class="btn btn-sm btn-outline-light">Print</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($transactions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No transactions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
