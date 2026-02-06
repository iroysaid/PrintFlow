<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="glass-panel p-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0 text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Weekend Sales Report</h2>
        <a href="/pos" class="btn btn-light">Back to POS</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-borderless" style="background: rgba(255,255,255,0.9); border-radius: 10px; overflow: hidden;">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4 text-center">Total Transactions</th>
                    <th class="py-3 px-4 text-end">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">No transactions found for this weekend.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                        $grandTotal = 0; 
                        $totalTrx = 0;
                    ?>
                    <?php foreach ($reports as $row): ?>
                        <?php 
                            $grandTotal += $row['total_revenue'];
                            $totalTrx += $row['total_transactions'];
                        ?>
                        <tr>
                            <td class="py-3 px-4"><?= date('l, d F Y', strtotime($row['date'])) ?></td>
                            <td class="py-3 px-4 text-center"><?= $row['total_transactions'] ?></td>
                            <td class="py-3 px-4 text-end fw-bold">Rp <?= number_format($row['total_revenue'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-light fw-bold border-top">
                        <td class="py-3 px-4">TOTAL</td>
                        <td class="py-3 px-4 text-center"><?= $totalTrx ?></td>
                        <td class="py-3 px-4 text-end text-primary">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
