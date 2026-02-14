<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixTransactionDetailsFK extends Migration
{
    public function up()
    {
        // 1. Rename existing table to backup
        $this->db->query("ALTER TABLE transaction_details RENAME TO transaction_details_old");

        // 2. Create new table with correct FK to 'products' (not products_old)
        $this->db->query("
            CREATE TABLE `transaction_details` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `transaction_id` INTEGER NOT NULL,
                `product_id` INTEGER NOT NULL,
                `panjang` DECIMAL NOT NULL DEFAULT 1,
                `lebar` DECIMAL NOT NULL DEFAULT 1,
                `qty` INTEGER NOT NULL DEFAULT 1,
                `catatan_finishing` TEXT NULL,
                `link_file` VARCHAR NULL,
                `subtotal` DECIMAL NOT NULL DEFAULT 0,
                `nama_project` VARCHAR NULL,
                `diskon_persen` INTEGER DEFAULT 0,
                CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            )
        ");

        // 3. Copy data from old table to new table
        // We match columns explicitly to be safe, though * usually works if schema is identical except constraints
        $this->db->query("INSERT INTO transaction_details SELECT * FROM transaction_details_old");

        // 4. Drop old table
        $this->db->query("DROP TABLE transaction_details_old");
    }

    public function down()
    {
        // No need to revert this fix really, as it just corrects a broken state.
    }
}
