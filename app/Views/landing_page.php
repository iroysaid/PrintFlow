<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wise Printing - Percetakan Digital Terbaik</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<style>
    .hover-zoom .card-img-top {
        transition: transform 0.5s ease;
    }
    .hover-zoom:hover .card-img-top {
        transform: scale(1.1);
    }
</style>
<body class="bg-white">

    <!-- Transparent Navbar for Landing -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-print me-2"></i>Wise Printing
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLanding">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navLanding">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item"><a class="nav-link text-white" href="#services">Services</a></li>
                    
                    <li class="nav-item"><a class="nav-link text-white" href="#pricelist">Pricelist</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#news">News & Promo</a></li>
                    
                    <?php if(session()->get('logged_in')): ?>
                        <li class="nav-item">
                            <a class="btn btn-warning fw-bold text-dark px-4" href="<?= (session()->get('role') == 'cashier') ? '/pos' : '/admin/dashboard' ?>">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- Login Button Hiden -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="d-flex align-items-center justify-content-center text-center text-white bg-primary bg-gradient" style="min-height: 80vh; padding-top: 60px;">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">Percetakan Digital Tercepat & Terbaik</h1>
            <p class="lead mb-4 opacity-75">Solusi cetak spanduk, sticker, dan kartu nama dengan kualitas premium.</p>
            <a href="https://wa.me/6281253714657" target="_blank" class="btn btn-warning btn-lg fw-bold text-dark px-5 py-3 shadow">PESAN SEKARANG</a>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">Layanan Kami</h2>
                <p class="text-muted">Kualitas terbaik untuk segala kebutuhan cetak Anda</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="mb-3 text-primary"><i class="fas fa-scroll fa-3x"></i></div>
                        <h4 class="fw-bold">MMT / Spanduk</h4>
                        <p class="text-muted">Cetak spanduk outdoor tahan cuaca dengan resolusi tinggi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="mb-3 text-warning"><i class="fas fa-sticky-note fa-3x"></i></div>
                        <h4 class="fw-bold">Stiker Label</h4>
                        <p class="text-muted">Stiker Chromo, Vinyl, dan Transparan untuk kemasan produk.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="mb-3 text-success"><i class="fas fa-id-card fa-3x"></i></div>
                        <h4 class="fw-bold">Kartu Nama</h4>
                        <p class="text-muted">Cetak kartu nama premium dengan finishing laminasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </section>

    <!-- News & Promos -->
    <section id="news" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">Berita & Promo</h2>
                <p class="text-muted">Update terbaru dari workshop kami</p>
            </div>

            <div class="row g-4">
                <?php if(!empty($promos)): ?>
                    <?php foreach($promos as $promo): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden hover-zoom">
                            <div class="ratio bg-secondary" style="--bs-aspect-ratio: 133.33%;">
                                <?php if($promo['image']): ?>
                                    <img src="/uploads/content/<?= $promo['image'] ?>" class="card-img-top object-fit-cover transition-transform" alt="<?= esc($promo['title']) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="fw-bold"><?= esc($promo['title']) ?></h5>
                                <p class="card-text text-muted small"><?= esc($promo['content']) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback to Posts if Promos empty (Previous Logic) -->
                     <?php foreach($posts as $post): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden hover-zoom">
                            <div class="ratio ratio-4x3 bg-secondary">
                                <?php if($post['image']): ?>
                                    <img src="/uploads/<?= $post['image'] ?>" class="card-img-top object-fit-cover transition-transform" alt="Promo">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body">
                                <span class="badge bg-primary mb-2 text-uppercase"><?= $post['category'] ?></span>
                                <h5 class="fw-bold"><?= $post['title'] ?></h5>
                                <p class="card-text text-muted small"><?= substr($post['content'], 0, 100) ?>...</p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <small class="text-muted"><?= date('d M Y', strtotime($post['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Pricelist Section -->
    <section id="pricelist" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">Daftar Harga</h2>
                <p class="text-muted">Harga transparan & terbaik untuk kebutuhan cetak Anda</p>
            </div>
            
            <div class="row g-4">
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $product): ?>
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border-0 shadow-sm hover-up transition-all">
                            <div class="card-body text-center p-4 d-flex flex-column">
                                <div class="mb-3 text-primary">
                                    <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-tag fa-lg"></i>
                                    </div>
                                </div>
                                
                                <h5 class="fw-bold mb-auto"><?= esc($product['nama_barang']) ?></h5>
                                
                                <div class="mt-3 pt-3 border-top">
                                    <span class="d-block text-muted small text-uppercase fw-bold mb-1">Mulai Dari</span>
                                    <span class="fs-4 fw-bold text-dark">
                                        Rp <?= number_format($product['harga_dasar'], 0, ',', '.') ?>
                                    </span>
                                    <span class="text-muted small">
                                        / <?= $product['jenis_harga'] == 'meter' ? 'm' : 'pcs' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Sedang memperbarui harga...</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="https://wa.me/6281253714657" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fab fa-whatsapp me-2"></i>Tanya Harga Spesifik
                </a>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <h4 class="fw-bold mb-3">Wise Printing Digital Printing</h4>
            <p class="opacity-75 mb-4">Solusi cetak cepat, murah, dan berkualitas.</p>
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="https://wa.me/6281253714657" class="text-white" target="_blank"><i class="fab fa-whatsapp fa-lg"></i></a>
            </div>
            <small class="opacity-50">&copy; <?= date('Y') ?> Wise Printing. Version 1.01 | Developed by iroysaid</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
