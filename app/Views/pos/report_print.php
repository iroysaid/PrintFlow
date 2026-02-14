<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $startDateUnformatted = strtotime($start_date);
        $endDateUnformatted = strtotime($end_date);
        
        if (date('Y-m-d', $startDateUnformatted) === date('Y-m-d', $endDateUnformatted)) {
            $dateTitle = date('d-m-Y', $startDateUnformatted);
            $dateHeader = date('d M Y', $startDateUnformatted);
        } else {
            $dateTitle = date('d-m-Y', $startDateUnformatted) . '_sd_' . date('d-m-Y', $endDateUnformatted);
            $dateHeader = date('d M Y', $startDateUnformatted) . ' - ' . date('d M Y', $endDateUnformatted);
        }
    ?>
    <title>Laporan_Penjualan_<?= $dateTitle ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        
        @page {
            size: A4;
            margin: 20mm; /* Added margins */
        }

        .header {
            text-align: left;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .meta {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table th, .report-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .report-table td.text-center, .text-center { text-align: center !important; }
        .report-table td.text-right, .text-right { text-align: right !important; }

        .report-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }



        .summary {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                padding: 0;
                margin: 0;
                /* Force Desktop Width */
                width: 210mm; 
                max-width: 210mm;
            }
            .header, .meta, .report-table, .summary, .footer {
                width: 100% !important;
            }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            .no-print { display: none !important; }
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
<body>

    <!-- On screen print button, hidden in print and excel -->
    <?php if(!isset($is_excel)): ?>
    <!-- Floating Print Controls -->
    <?php // if(!isset($is_excel)): Check already performed above ?>
    <div class="no-print">
        <span class="label"><i class="fas fa-print"></i> Laporan</span>
        
        <a href="javascript:window.print()" class="btn-print">
            <i class="fas fa-print"></i> <span>Print</span>
        </a>
        
        <a href="javascript:savePDF()" class="btn-pdf">
            <i class="fas fa-file-pdf"></i> <span>Save PDF</span>
        </a>
        
        <a href="/pos/history" class="btn-back">
            <i class="fas fa-arrow-left"></i> <span>Kembali</span>
        </a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function savePDF() {
            const element = document.body;
            const opt = {
                margin: 0,
                filename: document.title + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Hide buttons before generating (though .no-print should handle it, this is extra safety)
            const btnDiv = document.querySelector('.no-print');
            btnDiv.style.display = 'none';

            html2pdf().set(opt).from(element).save().then(() => {
                // Show buttons again
                btnDiv.style.display = 'block';
            });
        }
    </script>
    <?php endif; ?>

    <div class="header">
        <div style="display: flex; align-items: center; justify-content: flex-start; gap: 15px; margin-bottom: 10px;">
            <img src="/logo_wise_black.svg" alt="Wise Printing Logo" style="height: 80px; width: auto;">
            <div style="text-align: left;">
                <h1 style="color: #2563eb; font-size: 24px; text-transform: uppercase; margin: 0; letter-spacing: 1px;">Wise Printing</h1>
                <p style="margin: 5px 0 0; font-size: 11px; color: #64748b; line-height: 1.4;">JL. MULAWARMAN NO.44 RT.48 MANGGAR BARU <br>BALIKPAPAN TIMUR<br>WA: 0812-3456-7890</p>
            </div>
        </div>
        <h2 style="margin: 10px 0 0; font-size: 18px; text-transform: uppercase; border-top: 2px solid #000; padding-top: 10px;">Laporan Penjualan</h2>
    </div>

    <div class="meta">
        <div>
            <strong>Periode:</strong> <?= $dateHeader ?>
        </div>
        <div>
            <strong>Dicetak:</strong> <?= date('d M Y H:i:s') ?>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal / Invoice</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 35%;">Rincian Produk</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1; 
            foreach($transactions as $t): 
            ?>
            <tr>
                <td class="text-center" style="vertical-align: top;"><?= $i++ ?></td>
                <td style="vertical-align: top;">
                    <?= date('d/m/Y', strtotime($t['tgl_masuk'])) ?><br>
                    <strong><?= esc($t['no_invoice']) ?></strong>
                </td>
                <td style="vertical-align: top;">
                    <strong><?= esc($t['customer_name']) ?></strong><br>
                    <small style="color:#666"><?= esc($t['customer_phone']) ?></small>
                </td>
                <td style="vertical-align: top;">
                    <?php if(!empty($t['items'])): ?>
                        <ul style="margin: 0; padding-left: 15px; font-size: 11px;">
                        <?php foreach($t['items'] as $item): ?>
                            <li>
                                <strong><?= esc($item['nama_barang']) ?></strong>
                                <?php if($item['panjang'] > 0 && $item['lebar'] > 0): ?>
                                    (<?= $item['panjang'] ?>m x <?= $item['lebar'] ?>m)
                                <?php endif; ?>
                                - <?= $item['qty'] ?> pcs
                                - Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
                <td class="text-center" style="vertical-align: top;">
                    <?= ucfirst($t['status_bayar']) ?>
                </td>
                <td class="text-right" style="vertical-align: top;">Rp <?= number_format($t['grand_total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($transactions)): ?>
            <tr>
                <td colspan="6" class="text-center">Tidak ada transaksi ditemukan untuk periode ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL</th>
                <th class="text-right">Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dihasilkan oleh Sistem PrintFlow
    </div>

    <script>
        // Auto print when opened
        window.onload = function() {
            // window.print(); // Optional: Auto trigger print
        }
    </script>
</body>
</html>
