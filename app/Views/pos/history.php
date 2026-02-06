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
                            <th class="ps-4">Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $t): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= $t['no_invoice'] ?></td>
                            <td class="text-muted"><?= date('d M Y H:i', strtotime($t['tgl_masuk'])) ?></td>
                            <td>
                                <!-- Fetching customer name could be done via join in Model, keeping simple for now -->
                                <span class="badge border border-secondary text-secondary rounded-0">ID: <?= $t['customer_id'] ?? '-' ?></span>
                            </td>
                            <td>
                                <?php if($t['status_bayar'] == 'lunas'): ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-primary fw-bold">Rp <?= number_format($t['grand_total'], 0, ',', '.') ?></td>
                            <td class="text-end pe-4">
                                <a href="/pos/printInvoice/<?= $t['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Reprint</a>
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
