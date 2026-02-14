<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $transaction['no_invoice'] ?> | Wise Printing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
            background: #fff;
        @media print {
            @page {
                size: A4;
                margin: 0; /* Use 0 here to avoid double margins with body padding */
            }
            body {
                margin: 0;
                padding: 2.5cm !important; /* Force margin via padding on body */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                width: 100%;
            }
            .page {
                border: none;
                padding: 0;
                margin: 0;
                width: 100%;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
        .invoice-header h1 {
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0;
        }
        .invoice-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 20px;
        }
        .brand h1 {
            color: var(--primary);
            font-size: 28px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .brand p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #64748b;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            font-size: 32px;
            color: #ccc;
            text-transform: uppercase;
        }
        .invoice-title p {
            margin: 5px 0 0;
            font-weight: bold;
            font-size: 16px;
        }

        /* Info Grid */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .info-box h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #64748b;
            margin: 0 0 10px;
        }
        .info-box table td {
            padding: 2px 10px 2px 0;
            font-size: 14px;
        }
        .info-box table td:first-child {
            font-weight: bold;
            color: #475569;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: var(--light);
            text-align: left;
            padding: 12px;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
            color: #475569;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .items-table th:last-child, 
        .items-table td:last-child {
            text-align: right;
        }
        .items-table .item-name {
            font-weight: 600;
            color: var(--primary);
        }
        .items-table .item-meta {
            font-size: 12px;
            color: #64748b;
            display: block;
        }

        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 50px;
        }
        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            text-align: right;
        }
        .totals-table tr:last-child td {
            border-top: 2px solid var(--dark);
            font-weight: bold;
            font-size: 18px;
            padding-top: 15px;
        }
        .totals-table .label {
            color: #64748b;
        }

        /* Payment Status */
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .status-paid {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #166534;
        }
        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #991b1b;
        }

        /* Footer / Signatures */
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-bottom: 1px solid #ccc;
            margin-top: 60px;
            margin-bottom: 10px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 14px;
        }

        /* Print Controls */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--dark);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .no-print a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }

        @media print {
            body { 
                background: white; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .page { width: 100%; border: none; padding: 0; margin: 0; }
            .no-print { display: none; }
            @page { margin: 0; size: A4; } /* Zero margin on page, let .page class handle padding */
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <a href="javascript:window.print()"><i class="fas fa-print"></i> Print</a>
        <?php 
        $source = $_GET['from'] ?? 'pos';
        $backLink = ($source === 'history') ? '/pos/history' : '/pos';
        $backText = ($source === 'history') ? 'Back to History' : 'Back to POS';
        ?>
        <a href="<?= $backLink ?>"><i class="fas fa-arrow-left"></i> <?= $backText ?></a>
    </div>

    <div class="page">
        <!-- Header -->
        <header class="invoice-header">
            <div class="brand">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="/logo_wise_black.svg" alt="Wise Printing Logo" style="height: 80px; width: auto;">
                    <div>
                        <h1>Wise Printing</h1>
                        <p style="margin-top: 5px; line-height: 1.4;">JL. MULAWARMAN NO.44 RT.48 MANGGAR BARU <br>BALIKPAPAN TIMUR<br>WA: 0812-3456-7890</p>
                    </div>
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
                <h3>Billed To:</h3>
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><?= htmlspecialchars($transaction['customer_name']) ?></td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td><?= htmlspecialchars($transaction['customer_phone']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="info-box">
                <h3>Order Details:</h3>
                <table>
                    <tr>
                        <td>Date:</td>
                        <td><?= date('d M Y, H:i', strtotime($transaction['tgl_masuk'])) ?></td>
                    </tr>
                    <tr>
                        <td>Estimate:</td>
                        <td><?= date('d M Y', strtotime($transaction['tgl_selesai'])) ?></td>
                    </tr>
                    <tr>
                        <td>Cashier:</td>
                        <td>user_<?= $transaction['user_id'] ?></td> <!-- Can replace with name via Join if needed -->
                    </tr>
                </table>
            </div>
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 40%">Description</th>
                    <th style="width: 15%" class="text-right">Price</th>
                    <th style="width: 10%" class="text-right">Qty</th>
                    <th style="width: 15%" class="text-right">Dims (m)</th>
                    <th style="width: 15%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <span class="item-name"><?= htmlspecialchars($item['nama_barang']) ?></span>
                        <?php if(!empty($item['nama_project'])): ?>
                            <span class="item-meta" style="color: #2563eb; font-weight: 500;">Project: <?= htmlspecialchars($item['nama_project']) ?></span>
                        <?php endif; ?>
                        <?php if(!empty($item['catatan'])): ?>
                            <span class="item-meta">Note: <?= htmlspecialchars($item['catatan']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;"><?= number_format($item['harga_dasar'], 0, ',', '.') ?></td>
                    <td style="text-align: right;"><?= $item['qty'] ?></td>
                    <td style="text-align: right;">
                        <?php if($item['jenis_harga'] == 'meter'): ?>
                            <?= $item['panjang'] ?> x <?= $item['lebar'] ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right; font-weight: 500;">
                        <?= number_format($item['subtotal'], 0, ',', '.') ?>
                        <?php if(!empty($item['diskon_persen']) && $item['diskon_persen'] > 0): ?>
                            <br><small style="color: #166534;">(Disc <?= $item['diskon_persen'] ?>%)</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totals and Status -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="padding-top: 20px;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #64748b; margin-bottom: 10px;">Payment Status</h3>
                <?php if($transaction['status_bayar'] == 'lunas'): ?>
                    <span class="status-badge status-paid">PAID / LUNAS</span>
                <?php else: ?>
                    <span class="status-badge status-unpaid">UNPAID / BELUM LUNAS</span>
                <?php endif; ?>
                
                <div style="margin-top: 20px; font-size: 13px; color: #64748b;">
                    Payment Method: <strong style="color: #333; text-transform: uppercase;"><?= $transaction['metode_bayar'] ?></strong>
                </div>
            </div>

            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td>Rp <?= number_format($transaction['total_asli'], 0, ',', '.') ?></td>
                    </tr>
                    <?php if($transaction['diskon_persen'] > 0 || $transaction['diskon'] > 0): ?>
                    <tr>
                        <td class="label" style="color: #166534;">Discount (<?= $transaction['diskon_persen'] ?>%)</td>
                        <td style="color: #166534;">- Rp <?= number_format($transaction['diskon'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="label">Grand Total</td>
                        <td style="font-size: 20px; color: var(--primary);">Rp <?= number_format($transaction['grand_total'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Amount Paid</td>
                        <td>Rp <?= number_format($transaction['nominal_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php if($transaction['sisa_bayar'] > 0): ?>
                    <tr>
                        <td class="label" style="color: #991b1b; font-weight: bold;">Balance Due</td>
                        <td style="color: #991b1b; font-weight: bold;">Rp <?= number_format($transaction['sisa_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Footers -->
        <div class="footer">
            <div class="signature-box">
                <p style="margin-bottom: 50px;">Customer,</p>
                <div class="signature-line"></div>
                <div class="signature-name"><?= htmlspecialchars($transaction['customer_name']) ?></div>
            </div>
            
            <div style="text-align: center; width: 300px; color: #64748b; font-size: 12px; align-self: flex-end; margin-bottom: 20px;">
                <p>Thank you for your business!</p>
                <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan kecuali ada perjanjian.</p>
            </div>

            <div class="signature-box">
                <p style="margin-bottom: 50px;">Authorized Sign,</p>
                <div class="signature-line"></div>
                <div class="signature-name">Wise Printing</div>
            </div>
        </div>
    </div>

</body>
</html>
