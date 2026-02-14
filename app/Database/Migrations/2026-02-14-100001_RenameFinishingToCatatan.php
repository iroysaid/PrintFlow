<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameFinishingToCatatan extends Migration
{
    public function up()
    {
        // SQLite doesn't support RENAME COLUMN directly in older versions, but CodeIgniter might abstract it.
        // However, safe bet for SQLite is usually strict reconstruction for FKs.
        // But let's try strict RENAME COLUMN first if supported, else reconstruction.
        // Actually, SQLite 3.25.0+ supports RENAME COLUMN. CodeIgniter's Forge should handle it or we use raw query.
        
        // Let's use the reconstruction method to be safe and consistent with previous FK fixes.
        
        // 1. Rename existing table
        $this->db->query("ALTER TABLE transaction_details RENAME TO transaction_details_temp_rename");

         // 2. Create new table with 'catatan' instead of 'catatan_finishing'
        $this->db->query("
            CREATE TABLE `transaction_details` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `transaction_id` INTEGER NOT NULL,
                `product_id` INTEGER NOT NULL,
                `panjang` DECIMAL NOT NULL DEFAULT 1,
                `lebar` DECIMAL NOT NULL DEFAULT 1,
                `qty` INTEGER NOT NULL DEFAULT 1,
                `catatan` TEXT NULL, 
                `link_file` VARCHAR NULL,
                `subtotal` DECIMAL NOT NULL DEFAULT 0,
                `nama_project` VARCHAR NULL,
                `diskon_persen` INTEGER DEFAULT 0,
                CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            )
        ");

        // 3. Copy data
        $this->db->query("
            INSERT INTO transaction_details (id, transaction_id, product_id, panjang, lebar, qty, catatan, link_file, subtotal, nama_project, diskon_persen)
            SELECT id, transaction_id, product_id, panjang, lebar, qty, catatan_finishing, link_file, subtotal, nama_project, diskon_persen
            FROM transaction_details_temp_rename
        ");

        // 4. Drop old table
        $this->db->query("DROP TABLE transaction_details_temp_rename");
    }

    public function down()
    {
        // Revert process
         $this->db->query("ALTER TABLE transaction_details RENAME TO transaction_details_temp_revert");

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

        $this->db->query("
            INSERT INTO transaction_details (id, transaction_id, product_id, panjang, lebar, qty, catatan_finishing, link_file, subtotal, nama_project, diskon_persen)
            SELECT id, transaction_id, product_id, panjang, lebar, qty, catatan, link_file, subtotal, nama_project, diskon_persen
            FROM transaction_details_temp_revert
        ");

        $this->db->query("DROP TABLE transaction_details_temp_revert");
    }
}
