<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
/* Masonry Layout */
.masonry-grid {
    column-count: 1;
    column-gap: 1.5rem;
}
@media (min-width: 576px) {
    .masonry-grid { column-count: 2; }
}
@media (min-width: 992px) {
    .masonry-grid { column-count: 3; }
}
@media (min-width: 1400px) {
    .masonry-grid { column-count: 4; }
}

.masonry-item {
    break-inside: avoid;
    margin-bottom: 1.5rem;
}

/* Card Styling - Light Theme */
.prod-card {
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 16px;
    background: #fff; /* Light background */
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}
.prod-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.prod-header {
    background-color: #0d6efd !important; /* Force Blue */
    color: #fff;
    padding: 12px 20px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    font-size: 0.95rem;
}
/* Priority Colors */
.prod-header.urgent { background-color: #ffc107 !important; color: #000; } /* Yellow */
.prod-header.critical { background-color: #dc3545 !important; color: #fff; } /* Red */

.prod-body {
    padding: 20px;
    color: #444; /* Darker text */
    flex-grow: 1; /* Ensure body fills space */
}

/* Main Content: Finishing Notes */
.finishing-highlight {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    padding: 10px 15px;
    background-color: #eef2f7;
    border-left: 5px solid #0d6efd;
    border-radius: 6px;
    margin-bottom: 15px;
    /* Removed white-space: pre-wrap to avoid double indent if nl2br is used, but kept basic wrapping. */
    word-wrap: break-word;
}
.finishing-label {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #6c757d;
    font-weight: 700;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
}

/* Order Item Secondary Info */
.item-meta-line {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 4px;
    padding-bottom: 4px;
    border-bottom: 1px dashed #eee;
}
.item-meta-line:last-child { border-bottom: none; }

/* Floating Action Button (FAB) Style for Actions */
.prod-actions {
    display: flex;
    justify-content: flex-end;
    padding: 10px 20px 20px;
    gap: 0.5rem;
    flex-wrap: wrap;
    background: #fff;
    border-top: 1px solid #f0f0f0;
}

.chip-btn {
    border: 1px solid #e0e0e0;
    background: #f8f9fa;
    color: #666;
    border-radius: 50px; /* Fully rounded */
    padding: 6px 14px;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}
.chip-btn:hover {
    background: #e9ecef;
    color: #333;
    border-color: #ced4da;
    transform: translateY(-1px);
}
.chip-btn.active {
    background: #0d6efd; /* Blue */
    color: #fff;
    border-color: #0d6efd;
    box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
}
/* Specific colors for specific statuses if active */
.chip-btn.active[data-status="printing"] { background: #fd7e14; border-color: #fd7e14; box-shadow: 0 2px 6px rgba(253, 126, 20, 0.3); }
.chip-btn.active[data-status="finishing"] { background: #198754; border-color: #198754; box-shadow: 0 2px 6px rgba(25, 135, 84, 0.3); }
.chip-btn.active[data-status="done"] { background: #0dcaf0; border-color: #0dcaf0; color:#000; box-shadow: 0 2px 6px rgba(13, 202, 240, 0.3); }

/* Responsive adjustments */
@media (max-width: 575px) {
    .prod-actions { justify-content: center; }
}
</style>

<div class="px-4"> <!-- Added helper container directly to content -->
<div class="row mb-4 mt-4"> <!-- Increased top margin -->
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="text-dark fw-bold"><i class="fas fa-tasks me-2 text-primary"></i>Production Board</h2>
        <div class="small d-none d-md-block">
            <span class="badge bg-secondary me-2">Queue</span>
            <span class="badge bg-primary me-2">Design</span>
            <span class="badge bg-warning text-dark me-2">Printing</span>
            <span class="badge bg-success me-2">Finishing</span>
            <span class="badge bg-info text-dark">Done</span>
        </div>
    </div>
</div>

<div class="masonry-grid">
    <?php foreach($orders as $order): ?>
        <?php
            // Determine header color based on deadline proximity
            $headerClass = '';
            $deadlineText = '';
            if ($order['deadline']) {
                $deadline = strtotime($order['deadline']);
                $now = time();
                $diff = $deadline - $now;
                $deadlineText = date('d M H:i', $deadline);
                
                if ($diff < 86400 && $diff > 0) { // < 24 hours
                    $headerClass = 'urgent';
                } elseif ($diff < 0) { // Overdue
                    $headerClass = 'critical';
                }
            }

            // Prepare Header Info: Project Name & Product Name from First Item (if any)
            $headerTitle = "Order #" . $order['id'];
            $mainItem = !empty($order['items']) ? $order['items'][0] : null;
            if ($mainItem) {
                $projName = $mainItem['nama_project'] ?: 'No Name';
                $prodName = $mainItem['nama_barang'] ?: 'Unknown Product';
                $headerTitle = "#{$order['id']} - {$projName} ({$prodName})";
            }
        ?>
        
        <div class="masonry-item">
            <div class="prod-card">
                <div class="prod-header <?= $headerClass ?>">
                    <div class="d-flex align-items-center text-truncate" style="max-width: 85%;">
                        <i class="fas fa-clipboard-list me-2 opacity-75"></i>
                        <span class="text-truncate" title="<?= esc($headerTitle) ?>"><?= esc($headerTitle) ?></span>
                    </div>
                     <span class="small opacity-75 ms-2"><?= date('d M', strtotime($order['tgl_masuk'])) ?></span>
                </div>
                
                <div class="prod-body">
                    
                    <!-- Main Content: Finishing Notes -->
                    <?php 
                        // Aggregate notes if multiple items? For now take first or join all.
                        // Let's iterate to find any finishing notes.
                        $finishingNotes = [];
                        if(!empty($order['items'])) {
                            foreach($order['items'] as $item) {
                                if(!empty($item['catatan'])) {
                                    $finishingNotes[] = $item['catatan']; 
                                }
                            }
                        }
                        // Also include main order notes if deemed important, but user asked for finishing notes from POS.
                        // Let's combine them for clarity.
                        $allNotes = trim(implode("\n", $finishingNotes));
                    ?>
                    
                    <?php if(!empty($allNotes)): ?>
                        <div class="finishing-highlight">
                            <span class="finishing-label"><i class="fas fa-sticky-note me-1"></i>Notes</span>
                            <?= nl2br(esc($allNotes)) ?>
                        </div>
                    <?php else: ?>
                        <!-- Fallback if no specific finishing note -->
                        <div class="alert alert-light text-center text-muted small py-2 mb-3">
                            <em>No notes</em>
                        </div>
                    <?php endif; ?>

                    <!-- Secondary Info -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Customer & Status</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark"><?= esc($order['customer_name'] ?? 'Guest') ?></span>
                            <span class="badge bg-light text-primary border"><?= ucfirst($order['status_produksi']) ?></span>
                        </div>
                        <?php if($deadlineText): ?>
                            <div class="text-danger small mt-1 fw-bold"><i class="fas fa-clock me-1"></i> Due: <?= $deadlineText ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Items Detail List (Secondary) -->
                    <?php if(!empty($order['items'])): ?>
                        <div class="bg-light p-2 rounded mb-3">
                            <?php foreach($order['items'] as $item): ?>
                                <div class="item-meta-line">
                                    <strong><?= $item['qty'] ?>x</strong> <?= esc($item['nama_barang']) ?>
                                    <?php if($item['panjang'] > 0): ?>
                                        (<?= $item['panjang'] ?>x<?= $item['lebar'] ?>)
                                    <?php endif; ?>
                                    <?php if(!empty($item['link_file'])): ?>
                                         <a href="<?= esc($item['link_file']) ?>" target="_blank" class="ms-1 text-primary"><i class="fas fa-link"></i></a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($order['order_notes'])): ?>
                        <div class="small text-muted fst-italic border-top pt-2">
                            <i class="fas fa-info-circle me-1"></i>Note: <?= esc($order['order_notes']) ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="prod-actions">
                    <button class="chip-btn <?= $order['status_produksi'] == 'design' ? 'active' : '' ?>" 
                            onclick="updateStatus(<?= $order['id'] ?>, 'design')" title="Design">
                        <i class="fas fa-paint-brush"></i> Design
                    </button>
                    
                    <button class="chip-btn <?= $order['status_produksi'] == 'printing' ? 'active' : '' ?>" data-status="printing"
                            onclick="updateStatus(<?= $order['id'] ?>, 'printing')" title="Printing">
                        <i class="fas fa-print"></i> Print
                    </button>
                    
                    <button class="chip-btn <?= $order['status_produksi'] == 'finishing' ? 'active' : '' ?>" data-status="finishing"
                            onclick="updateStatus(<?= $order['id'] ?>, 'finishing')" title="Finishing/Packing">
                        <i class="fas fa-cut"></i> Finish/Pack
                    </button>
                    
                    <button class="chip-btn <?= $order['status_produksi'] == 'done' ? 'active' : '' ?>" data-status="done"
                            onclick="updateStatus(<?= $order['id'] ?>, 'done')" title="Done">
                        <i class="fas fa-check"></i> Done
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div> <!-- Close px-4 container -->

<script>
function updateStatus(id, newStatus) {
    let formData = new FormData();
    formData.append('status', newStatus);
    
    // Improved CSRF Token Handling
    const csrfName = document.querySelector('meta[name="csrf-header"]').getAttribute('content');
    const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Add CSRF to Headers AND FormData to be safe
    formData.append(csrfName, csrfHash); // For strict form checks
    
    let headers = {
        'X-Requested-With': 'XMLHttpRequest'
    };
    headers[csrfName] = csrfHash; // For API checks

    fetch('/production/updateStatus/' + id, {
        method: 'POST',
        headers: headers,
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if(data.success) {
            location.reload(); 
        } else {
            console.error('Error:', data);
            alert('Failed to update status: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('Connection error: ' + error.message);
    });
}
</script>

<?= $this->endSection() ?>
