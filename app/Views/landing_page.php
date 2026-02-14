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
    <link rel="shortcut icon" type="image/svg+xml" href="/favicon.svg">
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
                <img src="/images/logo.svg" alt="Wise Printing" height="40" class="me-2">
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
                        <div class="card border-0 shadow-sm h-100 overflow-hidden hover-zoom rounded-4">
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
                        <div class="card border-0 shadow-sm h-100 overflow-hidden hover-zoom rounded-4">
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
    <style>
        :root {
            --bs-blue: #0d6efd;
            --bs-indigo: #6610f2;
            --bg-surface: #f8fafc;
            --card-surface: #ffffff;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --border-color: #e2e8f0;
        }

        .pricelist-wrapper {
            background-color: var(--bg-surface);
            padding: 4rem 1rem;
        }

        .category-card {
            background: var(--card-surface);
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .category-header {
            background: linear-gradient(135deg, #0d6efd, #0099ff);
            padding: 1.25rem 1.5rem;
            color: white;
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.95rem;
        }

        .table-custom th {
            background-color: #f1f5f9;
            color: var(--text-sub);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .table-custom td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
            white-space: nowrap; /* Prevent wrapping for data columns */
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .table-custom tr:hover td {
            background-color: #f8fafc;
        }

        .table-custom td:first-child {
            font-weight: 600;
            color: var(--bs-blue);
            white-space: normal; /* Allow text wrapping for the first column */
            min-width: 180px; /* Ensure sufficient width */
            max-width: 250px;
            line-height: 1.4;
        }

        .note-row {
            background-color: #fffbeb;
            color: #b45309;
            font-size: 0.85rem;
            font-weight: 500;
            text-align: center;
        }

        /* Responsive Table */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <section id="pricelist" class="pricelist-wrapper">
        <div class="container" style="max-width: 900px;">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary display-5">Price List 2026</h2>
                <p class="text-muted lead">Harga transparan & terbaik untuk kebutuhan cetak Anda</p>
            </div>
            
            <div class="d-flex flex-column gap-4">

                <!-- Category: Digital Printing A3+ -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-print me-2"></i>Digital Printing A3+</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Bahan</th><th>Qty 1-50</th><th>Qty 51-100</th><th>>100</th><th>Duplex</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Art Paper 120 gsm</td><td>Rp3,700</td><td>Rp3,600</td><td>Rp3,500</td><td>Rp3,000</td></tr>
                                <tr><td>Art Paper 150 gsm</td><td>Rp4,000</td><td>Rp3,800</td><td>Rp3,700</td><td>Rp2,900</td></tr>
                                <tr><td>Art Paper 210 gsm</td><td>Rp5,000</td><td>Rp4,800</td><td>Rp4,600</td><td>Rp2,800</td></tr>
                                <tr><td>Art Paper 230 gsm</td><td>Rp5,700</td><td>Rp5,400</td><td>Rp5,200</td><td>-</td></tr>
                                <tr><td>Art Paper 260 gsm</td><td>Rp6,000</td><td>Rp5,700</td><td>Rp5,400</td><td>-</td></tr>
                                <tr><td>Sticker Bontak</td><td>Rp9,000</td><td>Rp8,000</td><td>Rp7,000</td><td>-</td></tr>
                                <tr><td>Sticker Vinyl</td><td>Rp12,000</td><td>Rp11,000</td><td>Rp10,000</td><td>-</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Print Dokumen (HVS) -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-file-alt me-2"></i>Print Dokumen (HVS)</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Tipe</th><th>Ukuran</th><th>Warna</th><th>Qty 1-50</th><th>>50</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Print Biasa</td><td>A4 / F4</td><td>BW</td><td>Rp500</td><td>Rp450</td></tr>
                                <tr><td>Print Biasa</td><td>A4 / F4</td><td>Warna</td><td>Rp900</td><td>Rp1.000</td></tr>
                                <tr><td>Print Premium</td><td>A4</td><td>BW</td><td>Rp800</td><td>Rp700</td></tr>
                                <tr><td>Print Premium</td><td>A4</td><td>Warna</td><td>Rp1,900</td><td>Rp2.000</td></tr>
                                <tr><td>Print Premium</td><td>A3</td><td>BW</td><td>Rp2,800</td><td>Rp2,700</td></tr>
                                <tr><td>Print Premium</td><td>A3</td><td>Warna</td><td>Rp3,200</td><td>Rp3,100</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Cetak Brosur / Flyer -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-newspaper me-2"></i>Cetak Brosur / Flyer</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Bahan</th><th>Ukuran</th><th>1 Sisi</th><th>2 Sisi</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Art Paper 150</td><td>A4</td><td>Rp650k</td><td>Rp1jt</td></tr>
                                <tr><td>Art Paper 150</td><td>A5</td><td>Rp375k</td><td>Rp600k</td></tr>
                                <tr><td>Art Paper 150</td><td>DL</td><td>Rp275k</td><td>Rp425k</td></tr>
                                <tr><td>Art Paper 210</td><td>A4</td><td>Rp750k</td><td>Rp1,1jt</td></tr>
                                <tr><td>Art Paper 210</td><td>A5</td><td>Rp425k</td><td>Rp660k</td></tr>
                                <tr><td>HVS 80 (Warna)</td><td>A4</td><td>Rp400k</td><td>Rp630k</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Merchandise & Foto -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-images me-2"></i>Merchandise & Foto</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Item</th><th>Spesifikasi</th><th>Harga</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Kartu Nama</td><td>AP 230gsm (1 Sisi)</td><td>Rp35,000 /box</td></tr>
                                <tr><td>Kartu Nama</td><td>AP 230gsm (2 Sisi)</td><td>Rp55,000 /box</td></tr>
                                <tr><td>ID Card</td><td>PVC Instant</td><td>Rp5k - 8k</td></tr>
                                <tr><td>Pas Foto</td><td>Semua Ukuran</td><td>Rp6,000 /paket</td></tr>
                                <tr><td>Cetak Foto</td><td>4R</td><td>Rp4,000</td></tr>
                                <tr><td>Cetak Foto</td><td>10R</td><td>Rp14,000</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Outdoor (Spanduk) -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-scroll me-2"></i>Outdoor (Spanduk)</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Bahan</th><th>Harga/m</th><th>Ket</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Flexi 280</td><td>Rp 20.000</td><td>Qty 1-5m</td></tr>
                                <tr><td>Flexi 280</td><td>Rp 17.000</td><td>Qty >16m</td></tr>
                                <tr><td>Korcin 440gsm</td><td>Rp 50.000</td><td>Tebal</td></tr>
                                <tr><td>Backlite (Neon)</td><td>Rp 80.000</td><td>Light</td></tr>
                                <tr><td>Sticker Outdoor</td><td>Rp 85.000</td><td>Ritrama</td></tr>
                                <tr><td>X-Banner</td><td>Rp 35.000</td><td>Tiang Saja</td></tr>
                                <tr><td>Roll Banner</td><td>Rp 140.000</td><td>Tiang Saja</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Stempel (Stamp) -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-stamp me-2"></i>Stempel (Stamp)</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Ukuran</th><th>Max Area</th><th>Harga</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Kecil</td><td>900 mm</td><td>Rp50,000</td></tr>
                                <tr><td>Sedang</td><td>1600 mm</td><td>Rp60,000</td></tr>
                                <tr><td>Besar</td><td>2300 mm</td><td>Rp70,000</td></tr>
                                <tr><td>Jumbo</td><td>3000 mm</td><td>Rp80,000</td></tr>
                                <tr><td colspan="3" class="note-row"><i class="fas fa-info-circle me-1"></i> Isi Ulang Tinta: Rp 5.000</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Laminasi (Finishing) -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-layer-group me-2"></i>Laminasi (Finishing)</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Tipe</th><th>Ukuran</th><th>Harga</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Roll Panas</td><td>A3+ (1 Sisi)</td><td>Rp1.500</td></tr>
                                <tr><td>Roll Panas</td><td>A3+ (2 Sisi)</td><td>Rp2.500</td></tr>
                                <tr><td>Laminasi Keras</td><td>A4</td><td>Rp5,000</td></tr>
                                <tr><td>Laminasi Dingin</td><td>A3+</td><td>Rp4,000</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category: Cetak Undangan -->
                <div class="category-card">
                    <div class="category-header"><h3 class="category-title"><i class="fas fa-envelope-open-text me-2"></i>Cetak Undangan</h3></div>
                    <div class="table-responsive-custom">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Kertas</th><th>Spek</th><th>Harga (200+)</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>HVS 80gsm</td><td>1 Sisi (BW)</td><td>Rp430</td></tr>
                                <tr><td>BC / Tik</td><td>160gsm (2 Sisi)</td><td>Rp1,050</td></tr>
                                <tr><td>Art Paper 230</td><td>Tanpa Laminasi</td><td>Rp2,500</td></tr>
                                <tr><td>Art Paper 230</td><td>Laminasi 1 sisi</td><td>Rp3,800</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            
            <div class="text-center mt-5">
                <a href="https://wa.me/6281253714657" target="_blank" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg">
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
