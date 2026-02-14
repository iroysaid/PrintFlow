<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <h2 class="text-white mb-4">Transaction History</h2>

        <!-- Search & Filter -->
        <div class="glass-panel mb-4 p-4">
            <form action="" method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="text-white small mb-1">Search</label>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Invoice, Name, or Phone..." value="<?= esc($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="text-white small mb-1">Start Date</label>
                    <input type="date" name="start_date" class="form-control bg-light border-0" value="<?= esc($start_date ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="text-white small mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control bg-light border-0" value="<?= esc($end_date ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-filter me-1"></i> Filter</button>
                    <button type="button" onclick="printReport()" class="btn btn-success fw-bold"><i class="fas fa-print me-1"></i> Print</button>
                </div>
            </form>
        </div>

        <script>
        function getDates() {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            if(!startDate || !endDate) {
                alert('Silahkan pilih tanggal awal dan akhir');
                return null;
            }
            return { startDate, endDate };
        }

        function printReport() {
            const dates = getDates();
            if(dates) {
                window.open(`/pos/printReport?start_date=${dates.startDate}&end_date=${dates.endDate}`, '_blank');
            }
        }

        function exportExcel() {
            const dates = getDates();
            if(dates) {
                window.location.href = `/pos/exportExcel?start_date=${dates.startDate}&end_date=${dates.endDate}`;
            }
        }
        </script>
        
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
                            <td class="ps-4 fw-bold">
                                <a href="/pos/printInvoice/<?= $t['id'] ?>?from=history" target="_blank" class="text-dark text-decoration-underline" title="Lihat Nota">
                                    <?= $t['no_invoice'] ?>
                                </a>
                            </td>
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
                                <div class="btn-group">
                                    <a href="/pos/printInvoice/<?= $t['id'] ?>?from=history" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Nota">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="/pos/editTransaction/<?= $t['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $t['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
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

<script>
    function confirmDelete(id) {
        if(confirm('Apakah Anda yakin ingin menghapus transaksi ini? Data yang dihapus tidak dapat dikembalikan.')) {
            window.location.href = '/pos/deleteTransaction/' + id;
        }
    }
</script>
<?= $this->endSection() ?>
