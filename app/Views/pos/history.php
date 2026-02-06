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
                            <th>Total</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $t): ?>
                        <tr>
                            <td class="ps-4 text-white"><?= $t['no_invoice'] ?></td>
                            <td class="text-muted"><?= date('d M Y H:i', strtotime($t['tanggal'])) ?></td>
                            <td>
                                <!-- Fetching customer name could be done via join in Model, keeping simple for now -->
                                <span class="badge border border-secondary text-secondary rounded-0">ID: <?= $t['customer_id'] ?></span>
                            </td>
                            <td class="text-warning fw-bold">Rp <?= number_format($t['total_bayar'], 0, ',', '.') ?></td>
                            <td class="text-end pe-4">
                                <a href="/pos/printInvoice/<?= $t['id'] ?>" target="_blank" class="btn btn-sm btn-outline-light">Reprint</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($transactions)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No transactions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
