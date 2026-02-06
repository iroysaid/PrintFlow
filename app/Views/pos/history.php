<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <h2 class="text-white mb-4">Transaction History</h2>

        <!-- Search & Filter -->
        <div class="glass-panel mb-4 p-4">
            <form action="" method="get" class="row g-3">
                <div class="col-md-4">
                    <label class="text-white small mb-1">Search</label>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Invoice, Name, or Phone..." value="<?= esc($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="text-white small mb-1">Dari Tanggal</label>
                    <input type="date" name="tgl_awal" class="form-control bg-light border-0" value="<?= esc($tglAwal) ?>">
                </div>
                <div class="col-md-3">
                    <label class="text-white small mb-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" class="form-control bg-light border-0" value="<?= esc($tglAkhir) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
        
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
                                <div class="fw-bold"><?= esc($t['customer_name']) ?></div>
                                <div class="small text-muted"><?= esc($t['customer_phone']) ?></div>
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
                                <a href="/pos/printInvoice/<?= $t['id'] ?>?from=history" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Nota">
                                    <i class="fas fa-file-invoice"></i> Nota
                                </a>
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
