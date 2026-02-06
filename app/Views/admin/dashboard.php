<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="fw-bold mb-4">Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-primary border-start-4 shadow-sm">
                <div class="card-body">
                    <small class="text-secondary fw-bold text-uppercase">Income Today</small>
                    <div class="fs-3 fw-bold mt-2">Rp <?= number_format($incomeToday, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-success border-start-4 shadow-sm">
                <div class="card-body">
                    <small class="text-secondary fw-bold text-uppercase">Orders Today</small>
                    <div class="fs-3 fw-bold mt-2"><?= $ordersToday ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-warning border-start-4 shadow-sm">
                <div class="card-body">
                    <small class="text-secondary fw-bold text-uppercase">In Production</small>
                    <div class="fs-3 fw-bold mt-2"><?= $onProgress ?></div>
                </div>
            </div>
        </div>
    </div>


        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-tasks me-2"></i>Production Queue</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Invoice</th>
                            <th>Customer</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($queue as $q): ?>
                        <tr>
                            <td class="ps-3 fw-bold"><?= esc($q['no_invoice']) ?></td>
                            <td>
                                <div><?= esc($q['customer_name']) ?></div>
                                <small class="text-muted"><?= esc($q['customer_phone']) ?></small>
                            </td>
                            <td>
                                <!-- Highlight if deadline is close/passed -->
                                <?php 
                                    $deadline = strtotime($q['tgl_selesai']);
                                    $today = time();
                                    $diff = round(($deadline - $today) / (60 * 60 * 24));
                                    $badgeClass = ($diff < 0) ? 'bg-danger' : (($diff <= 1) ? 'bg-warning text-dark' : 'bg-success');
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= date('d M Y', strtotime($q['tgl_selesai'])) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary text-uppercase"><?= $q['status_produksi'] ?></span>
                            </td>
                            <td>
                                <form action="/admin/dashboard/updateStatus/<?= $q['id'] ?>" method="post" class="d-inline">
                                    <div class="btn-group btn-group-sm">
                                        <?php if($q['status_produksi'] == 'queue'): ?>
                                            <button name="status" value="design" class="btn btn-outline-primary">Start Design</button>
                                        <?php elseif($q['status_produksi'] == 'design'): ?>
                                            <button name="status" value="printing" class="btn btn-outline-warning text-dark">Start Print</button>
                                        <?php elseif($q['status_produksi'] == 'printing'): ?>
                                            <button name="status" value="finishing" class="btn btn-outline-info text-dark">Finishing</button>
                                        <?php elseif($q['status_produksi'] == 'finishing'): ?>
                                            <button name="status" value="done" class="btn btn-success">Mark Done</button>
                                        <?php endif; ?>
                                        
                                        <?php if($q['status_produksi'] == 'done'): ?>
                                             <button name="status" value="taken" class="btn btn-outline-secondary">Taken</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($queue)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No active production tasks.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
