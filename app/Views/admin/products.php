<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark fw-bold"><i class="fas fa-boxes me-2 text-primary"></i>Inventory Management</h2>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-sort me-1"></i> Sort By
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?sort=newest"><i class="fas fa-clock me-2"></i>Newest</a></li>
                        <li><a class="dropdown-item" href="?sort=name_asc"><i class="fas fa-sort-alpha-down me-2"></i>Name (A-Z)</a></li>
                        <li><a class="dropdown-item" href="?sort=popular"><i class="fas fa-fire me-2"></i>Popular</a></li>
                    </ul>
                </div>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    + New Product
                </button>
            </div>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success rounded-0 mb-3 bg-transparent border-success text-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="glass-panel p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-primary">
                        <tr>
                            <th class="ps-4">Code</th>
                            <th>Image</th> <!-- New Column -->
                            <th>Name</th>
                            <th>Type</th>
                            <th>Base Price</th>
                            <th>Stock</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= esc($p['kode_barang']) ?></td>
                            <td>
                                <?php if($p['gambar']): ?>
                                    <img src="/uploads/products/<?= esc($p['gambar']) ?>" alt="Img" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted small" style="width: 40px; height: 40px;">No</div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($p['nama_barang']) ?></td>
                            <td>
                                <span class="badge <?= $p['jenis_harga'] == 'meter' ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                    <?= strtoupper($p['jenis_harga']) ?>
                                </span>
                            </td>
                            <td class="text-warning">Rp <?= number_format($p['harga_dasar'], 0, ',', '.') ?></td>
                            <td><?= $p['stok'] ?></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary" onclick="populateEdit(<?= htmlspecialchars(json_encode($p)) ?>)">Edit</button>
                                <a href="/admin/products/delete/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Are you sure?')">Del</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/products/create" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Product Image</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label>Code</label>
                        <input type="text" name="kode_barang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label>Type</label>
                            <select name="jenis_harga" class="form-select">
                                <option value="unit">Unit</option>
                                <option value="meter">Meter</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Base Price</label>
                            <input type="number" name="harga_dasar" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="edit_preview" src="" class="img-thumbnail" style="max-height: 150px; display: none;">
                    </div>
                    <div class="mb-3">
                        <label>Change Image</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label>Code</label>
                        <input type="text" id="edit_kode" name="kode_barang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" id="edit_nama" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label>Type</label>
                            <select id="edit_jenis" name="jenis_harga" class="form-select">
                                <option value="unit">Unit</option>
                                <option value="meter">Meter</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Base Price</label>
                            <input type="number" id="edit_harga" name="harga_dasar" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" id="edit_stok" name="stok" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function populateEdit(product) {
    document.getElementById('edit_kode').value = product.kode_barang;
    document.getElementById('edit_nama').value = product.nama_barang;
    document.getElementById('edit_jenis').value = product.jenis_harga;
    document.getElementById('edit_harga').value = product.harga_dasar;
    document.getElementById('edit_stok').value = product.stok;
    document.getElementById('editForm').action = '/admin/products/update/' + product.id;
    
    // Preview Image
    const preview = document.getElementById('edit_preview');
    if (product.gambar) {
        preview.src = '/uploads/products/' + product.gambar;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
        preview.src = '';
    }
    
    var myModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    myModal.show();
}
</script>

<?= $this->endSection() ?>
