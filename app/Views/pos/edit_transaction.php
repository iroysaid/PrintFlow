<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit me-2"></i>Edit Transaction #<?= $transaction['no_invoice'] ?></h5>
                </div>
                <div class="card-body">
                    
                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="/pos/updateTransaction/<?= $transaction['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Info</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="small text-muted">Name</label>
                                    <input type="text" name="nama_customer" class="form-control" value="<?= esc($transaction['customer_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Phone</label>
                                    <input type="text" name="no_hp" class="form-control" value="<?= esc($transaction['customer_phone']) ?>" required pattern="[0-9]*" inputmode="numeric">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status & Payment</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">Payment Status</label>
                                    <select name="status_bayar" class="form-select">
                                        <option value="lunas" <?= $transaction['status_bayar'] == 'lunas' ? 'selected' : '' ?>>LUNAS</option>
                                        <option value="belum_lunas" <?= $transaction['status_bayar'] == 'belum_lunas' ? 'selected' : '' ?>>BELUM LUNAS</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Production Status</label>
                                    <select name="status_produksi" class="form-select">
                                        <option value="queue" <?= $transaction['status_produksi'] == 'queue' ? 'selected' : '' ?>>Queue</option>
                                        <option value="process" <?= $transaction['status_produksi'] == 'process' ? 'selected' : '' ?>>Process</option>
                                        <option value="done" <?= $transaction['status_produksi'] == 'done' ? 'selected' : '' ?>>Done</option>
                                        <option value="taken" <?= $transaction['status_produksi'] == 'taken' ? 'selected' : '' ?>>Taken</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Grand Total (Read-only)</label>
                                    <input type="text" class="form-control bg-light" value="<?= number_format($transaction['grand_total'], 0, ',', '.') ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Nominal Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="nominal_bayar" class="form-control" value="<?= esc($transaction['nominal_bayar']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ordered Items (Read-only)</label>
                            <ul class="list-group">
                                <?php foreach($items as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold"><?= esc($item['nama_barang']) ?></div>
                                            <small class="text-muted">
                                                Qty: <?= $item['qty'] ?> 
                                                <?php if($item['jenis_harga'] == 'meter'): ?>
                                                    (<?= $item['panjang'] ?>x<?= $item['lebar'] ?>m)
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/pos/history" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-save me-2"></i>Save Changes</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
