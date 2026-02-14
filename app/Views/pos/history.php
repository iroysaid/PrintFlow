<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-4">
    <div class="row">
    <div class="col-12">
        <h2 class="text-dark fw-bold mb-4"><i class="fas fa-history me-2 text-primary"></i>Transaction History</h2>

        <!-- Search & Filter -->
        <style>
            .start-date-input::-webkit-calendar-picker-indicator {
                filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);
            }
            .end-date-input::-webkit-calendar-picker-indicator {
                filter: invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
            }
        </style>
        <div class="glass-panel mb-4 p-4">
            <form action="" method="get" class="row g-3 justify-content-center text-center" id="filterForm">
                <div class="col-md-3">
                    <label class="text-white small mb-1 d-block">Search</label>
                    <input type="text" name="search" class="form-control bg-light border-0 text-center" placeholder="Invoice, Name, or Phone..." value="<?= esc($search) ?>" id="searchInput">
                </div>
                <div class="col-md-3">
                    <label class="text-white small mb-1 d-block">Start Date</label>
                    <input type="date" name="start_date" class="form-control bg-light border-0 text-center start-date-input" value="<?= esc($start_date ?? date('Y-m-d')) ?>" id="startDateInput">
                </div>
                <div class="col-md-3">
                    <label class="text-white small mb-1 d-block">End Date</label>
                    <input type="date" name="end_date" class="form-control bg-light border-0 text-center end-date-input" value="<?= esc($end_date ?? date('Y-m-d')) ?>" id="endDateInput">
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-center gap-2">
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-filter me-1"></i> Filter</button>
                    <button type="button" onclick="printReport()" class="btn btn-success fw-bold"><i class="fas fa-print me-1"></i> Print</button>
                </div>
            </form>
        </div>

        <script>
        // Auto-filter script
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const inputs = form.querySelectorAll('input');
            let debounceTimer;

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchResults();
                    }, 500); // Debounce 500ms to allow typing
                });

                // Also trigger immediately on date change/selection if 'input' doesn't catch it generally
                if(input.type === 'date') {
                    input.addEventListener('change', function() {
                        fetchResults();
                    });
                }
            });

            function fetchResults() {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const url = window.location.pathname + '?' + params.toString();

                // Update URL without reloading
                window.history.pushState({}, '', url);

                // Fetch new table data
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableBody = doc.querySelector('tbody').innerHTML;
                        document.querySelector('tbody').innerHTML = newTableBody;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
        });

        function getDates() {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            if(!startDate || !endDate) {
                alert('Please select start and end date');
                return null;
            }
            return { startDate, endDate };
        }

        function printReport() {
            const dates = getDates();
            if(dates) {
                // Mobile Check
                if(window.innerWidth < 768) {
                    window.location.href = `/pos/printReport?start_date=${dates.startDate}&end_date=${dates.endDate}&from=history`;
                } else {
                    window.open(`/pos/printReport?start_date=${dates.startDate}&end_date=${dates.endDate}`, '_blank');
                }
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
                    <thead class="text-primary">
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
                                <a href="/pos/printInvoice/<?= $t['id'] ?>?from=history" target="_blank" class="text-dark text-decoration-underline" title="View Invoice">
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
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-primary fw-bold">Rp <?= number_format($t['grand_total'], 0, ',', '.') ?></td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="/pos/printInvoice/<?= $t['id'] ?>?from=history" target="_blank" class="btn btn-sm btn-outline-primary" title="View Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="/pos/editTransaction/<?= $t['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $t['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Delete">
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
        if(confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) {
            window.location.href = '/pos/deleteTransaction/' + id;
        }
    }
</script>
<?= $this->endSection() ?>
