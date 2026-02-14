<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold mb-0"><i class="fas fa-bullhorn me-2 text-primary"></i>Manage Promos (Home Page)</h2>
    </div>

    <!-- Promo Settings -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-4 justify-content-center">
                <?php if(!empty($promos)): ?>
                    <?php foreach($promos as $promo): ?>
                    <div class="col-md-3">
                        <div class="card h-100 border transition-all">
                            <div class="card-header bg-light fw-bold text-center">
                                Slot #<?= $promo['index_num'] ?>
                            </div>
                            <div class="card-body">
                                <form action="/admin/promo/update/<?= $promo['id'] ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <!-- Image Preview (3:4 Portrait) -->
                                    <div class="ratio mb-3 rounded overflow-hidden position-relative group-hover bg-secondary" style="--bs-aspect-ratio: 133.33%;">
                                        <?php if($promo['image']): ?>
                                            <img src="/uploads/content/<?= $promo['image'] ?>" class="w-100 h-100 object-fit-cover" id="preview_<?= $promo['id'] ?>" loading="lazy">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center w-100 h-100 text-white">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="small text-muted mb-1">Upload Image (3:4 Portrait)</label>
                                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                    </div>

                                    <div class="mb-3">
                                        <label class="small text-muted mb-1">Title</label>
                                        <input type="text" name="title" class="form-control form-control-sm" value="<?= esc($promo['title']) ?>" placeholder="Promo Title">
                                    </div>

                                    <div class="mb-2">
                                        <label class="small text-muted mb-1">Content</label>
                                        <textarea name="content" class="form-control form-control-sm" rows="3" placeholder="Short description..."><?= esc($promo['content']) ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2"><i class="fas fa-save me-1"></i> Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
