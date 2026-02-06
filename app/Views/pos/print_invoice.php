<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $transaction['no_invoice'] ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #333;
            padding-bottom: 10px;
        }
        
        .meta {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            text-align: left;
            padding: 8px 0;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            border-top: 1px dashed #333;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }

        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <a href="/pos" style="text-decoration: none; background: #ddd; padding: 10px 20px; border-radius: 5px; color: #333;">&larr; Back to POS</a>
    </div>

    <div class="header">
        <h2>MY STORE POS</h2>
        <p>Jl. Example No. 123, City<br>Telp: 0812-3456-7890</p>
    </div>

    <div class="meta">
        <div>
            No: <?= $transaction['no_invoice'] ?><br>
            Date: <?= date('d/m/Y H:i', strtotime($transaction['tanggal'])) ?>
        </div>
        <div class="text-right">
            Customer: <?= $customer ? $customer['nama_customer'] : 'Guest' ?><br>
            Cashier: Admin
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Price</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['nama_barang'] ?></td>
                <td class="text-right"><?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td class="text-right"><?= $item['qty'] ?></td>
                <td class="text-right"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            
            <tr class="total-row">
                <td colspan="3" class="text-right" style="padding-top: 15px;">TOTAL</td>
                <td class="text-right" style="padding-top: 15px;"><?= number_format($transaction['total_bayar'], 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>Please come again.</p>
    </div>

</body>
</html>
