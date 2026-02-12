<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveJenisHargaConstraint extends Migration
{
    public function up()
    {
        // 1. Rename existing table
        $this->db->query("ALTER TABLE products RENAME TO products_old");

        // 2. Create new table without CHECK constraint
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'primary_key'    => true,
                'auto_increment' => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jenis_harga' => [
                'type'       => 'VARCHAR', // Changed from ENUM/TEXT CHECK
                'constraint' => '50',
                'default'    => 'unit',
            ],
            'harga_dasar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME', 
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME', 
                'null' => true,
            ],
        ]);
        $this->forge->createTable('products');

        // 3. Copy data
        $this->db->query("INSERT INTO products SELECT * FROM products_old");

        // 4. Drop old table
        $this->db->query("DROP TABLE products_old");
    }

    public function down()
    {
        // Irreversible without complex logic, but for now we can just leave it as VARCHAR since it's more flexible.
    }
}
