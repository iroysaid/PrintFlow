<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $transaction['no_invoice'] ?> | Wise Printing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #000;
            --dark: #000;
            --light: #f3f3f3;
        }
        
        /* Screen View (Responsive) */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            background: #f0f0f0; /* Light gray background for screen to distinguish paper */
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }

        .page {
            background: white;
            padding: 20px 40px; /* Internal padding for screen reading */
            margin: 0 auto;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            /* max-width removed for full width responsive */
            width: auto; 
        }
        
        /* Print View (Strict A4) */
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
            body { 
                background: white;
                margin: 0;
                padding: 0;
                /* Force Desktop Width */
                width: 210mm; 
                max-width: 210mm;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page {
                width: 100% !important;
                max-width: none !important;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none !important;
            }
            
            /* Force small fonts for print */
            h1 { font-size: 16pt !important; }
            h2 { font-size: 20pt !important; }
            h3 { font-size: 9pt !important; }
            p, td, th, span, div { font-size: 9pt !important; }
            
            /* Compact spacing for print */
            .invoice-header { margin-bottom: 15px !important; padding-bottom: 5px !important; }
            .info-grid { margin-bottom: 15px !important; }
            .items-table { margin-bottom: 15px !important; }
            .footer { margin-top: 30px !important; }
            .signature-line { margin-top: 40px !important; }
        }

        /* Shared Components */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .brand img {
            height: 60px;
            width: auto;
        }
        .brand-text h1 {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .brand-text p {
            font-size: 12px;
            margin: 0;
            color: #555;
            line-height: 1.2;
        }

        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 30px;
            margin: 0;
            font-weight: 900;
            letter-spacing: 2px;
            color: #ccc;
        }
        .invoice-title p {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        /* Info Grid */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 40px;
        }
        .info-box {
            flex: 1;
        }
        .info-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
            color: #000;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 13px;
        }
        .info-table td:first-child {
            width: 80px;
            color: #555;
        }
        .info-table td:last-child {
            font-weight: 600;
            color: #000;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-left { text-align: left !important; }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
        }
        .items-table th {
            background-color: #f8f8f8;
            border-bottom: 2px solid #000;
            border-top: 2px solid #000;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
            font-size: 13px;
        }
        .item-name {
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .item-meta {
            font-size: 11px;
            color: #666;
            display: block;
            line-height: 1.2;
        }

        /* Totals */
        .totals-section {
            width: 40%;
            margin-left: auto;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 0;
            text-align: right;
            font-size: 12px;
        }
        .totals-table td:first-child {
            color: #555;
            padding-right: 15px;
        }
        .totals-table td:last-child {
            font-weight: bold;
            color: #000;
        }
        .totals-table .grand-total td {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            font-size: 18px;
            font-weight: 900;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        .signature-text {
            font-size: 12px;
            font-weight: bold;
        }

        /* Print Button / Toolbar - Enhanced */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            border-radius: 50px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .no-print span.label {
            font-weight: 700;
            color: #333;
            margin-right: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            padding-left: 5px;
        }
        .no-print a {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .no-print a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .no-print a:active {
            transform: translateY(0);
        }
        
        .btn-print { background: linear-gradient(135deg, #0d6efd, #0a58ca); }
        .btn-pdf { background: linear-gradient(135deg, #dc3545, #b02a37); }
        .btn-back { background: linear-gradient(135deg, #6c757d, #495057); }

        /* Mobile specific styles */
        @media screen and (max-width: 768px) {
            .no-print {
                top: auto;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                width: 90%; /* Floating bottom bar */
                max-width: 400px;
                justify-content: space-between;
                right: auto;
                background: rgba(255, 255, 255, 0.95);
                padding: 12px;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            }
            .no-print span.label {
                display: none; /* Hide label on mobile */
            }
            .no-print a {
                padding: 10px 0;
                font-size: 12px;
                flex: 1;
                justify-content: center;
                margin: 0 4px;
                flex-direction: column; /* Stack icon and text */
                gap: 4px;
                border-radius: 12px;
            }
            .no-print a i {
                font-size: 16px;
                margin-bottom: 2px;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <span class="label"><i class="fas fa-print"></i> Mode Preview</span>
        
        <a href="javascript:window.print()" class="btn-print">
            <i class="fas fa-print"></i> <span>Print</span>
        </a>
        
        <a href="javascript:savePDF()" class="btn-pdf">
            <i class="fas fa-file-pdf"></i> <span>Save PDF</span>
        </a>
        
        <a href="<?= isset($_GET['from']) && $_GET['from'] == 'pos' ? '/pos' : (isset($_GET['from']) && $_GET['from'] == 'history' ? '/pos/history' : '/pos') ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> <span>Kembali</span>
        </a>
    </div>

    <!-- HTML2PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function savePDF() {
            const element = document.body;
            const opt = {
                margin: 0,
                filename: 'Invoice_<?= $transaction['no_invoice'] ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Hide buttons before generating
            const btnDiv = document.querySelector('.no-print');
            btnDiv.style.display = 'none';

            html2pdf().set(opt).from(element).save().then(() => {
                // Show buttons again
                btnDiv.style.display = 'block';
            });
        }
    </script>

    <div class="page">
        <!-- Header -->
        <header class="invoice-header">
            <div class="brand">
                <img src="/logo_wise_black.svg" alt="Wise Printing Logo">
                <div class="brand-text">
                    <h1>Wise Printing</h1>
                    <p>JL. MULAWARMAN NO.44 RT.48 MANGGAR BARU <br>BALIKPAPAN TIMUR (WA: 0812-3456-7890)</p>
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p>#<?= $transaction['no_invoice'] ?></p>
            </div>
        </header>

        <!-- Info -->
        <div class="info-grid">
            <div class="info-box">
                <h3>Customer</h3>
                <table class="info-table">
                    <tr>
                        <td>Nama</td>
                        <td>: <?= htmlspecialchars($transaction['customer_name']) ?></td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: <?= htmlspecialchars($transaction['customer_phone']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="info-box">
                <h3>Detail Order</h3>
                <table class="info-table">
                    <tr>
                        <td>Tanggal</td>
                        <td>: <?= date('d/m/Y H:i', strtotime($transaction['tgl_masuk'])) ?></td>
                    </tr>
                    <tr>
                        <td>Selesai</td>
                        <td>: <?= date('d/m/Y', strtotime($transaction['tgl_selesai'])) ?></td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td>: Admin</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 40%; text-align: left;">Deskripsi Item</th>
                    <th style="width: 15%; text-align: right;">Harga</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 15%; text-align: center;">Ukuran</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): ?>
                <tr>
                    <td class="text-center" style="vertical-align: top;"><?= $i++ ?></td>
                    <td>
                        <span class="item-name"><?= htmlspecialchars($item['nama_barang']) ?></span>
                        <?php if(!empty($item['nama_project'])): ?>
                            <span class="item-meta">Projek: <?= htmlspecialchars($item['nama_project']) ?></span>
                        <?php endif; ?>
                        <?php if(!empty($item['catatan'])): ?>
                            <span class="item-meta">Note: <?= htmlspecialchars($item['catatan']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right" style="vertical-align: top;"><?= number_format($item['harga_dasar'], 0, ',', '.') ?></td>
                    <td class="text-center" style="vertical-align: top;"><?= $item['qty'] ?></td>
                    <td class="text-center" style="vertical-align: top;">
                        <?php if($item['jenis_harga'] == 'meter'): ?>
                            <?= $item['panjang'] ?>x<?= $item['lebar'] ?>m
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="text-right" style="vertical-align: top; font-weight: bold;">
                        <?= number_format($item['subtotal'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Footer / Totals -->
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 50%;">
                <!-- Status Pembayaran / Notes -->
                 <div style="border: 1px dashed #ccc; padding: 15px; border-radius: 5px; font-size: 12px; color: #555;">
                    <strong>Metode Pembayaran:</strong> <?= strtoupper($transaction['metode_bayar']) ?> <br>
                    <strong>Status:</strong> 
                    <?php if($transaction['status_bayar'] == 'lunas'): ?>
                        <span style="color: green; font-weight: bold;">LUNAS</span>
                    <?php else: ?>
                        <span style="color: red; font-weight: bold;">BELUM LUNAS</span>
                    <?php endif; ?>
                    <br><br>
                    <i class="fas fa-info-circle"></i> Barang yang sudah dicetak tidak dapat dibatalkan. Komplain maksimal 1x24 jam.
                 </div>
            </div>

            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td><?= number_format($transaction['total_asli'], 0, ',', '.') ?></td>
                    </tr>
                    <?php if($transaction['diskon_persen'] > 0 || $transaction['diskon'] > 0): ?>
                    <tr>
                        <td>Diskon</td>
                        <td>- <?= number_format($transaction['diskon'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="grand-total">
                        <td>TOTAL</td>
                        <td><?= number_format($transaction['grand_total'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Bayar</td>
                        <td><?= number_format($transaction['nominal_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php if($transaction['sisa_bayar'] > 0): ?>
                    <tr>
                        <td style="color: red;">Sisa Tagihan</td>
                        <td style="color: red; font-weight: bold;"><?= number_format($transaction['sisa_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Signatures -->
        <div class="footer">
            <div class="signature-box">
                <p>Penerima,</p>
                <div class="signature-line"></div>
                <div class="signature-text"><?= htmlspecialchars($transaction['customer_name']) ?></div>
            </div>
            
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="signature-line"></div>
                <div class="signature-text">Wise Printing</div>
            </div>
        </div>
    </div>

</body>
</html>
