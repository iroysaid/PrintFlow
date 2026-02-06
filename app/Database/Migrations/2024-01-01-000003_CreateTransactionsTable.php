<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'no_invoice' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'customer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'tgl_masuk' => [
                'type' => 'DATETIME',
            ],
            'estimasi_hari' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],
            'tgl_selesai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'total_asli' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'diskon' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'grand_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'nominal_bayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'comment'    => 'DP or Full Payment'
            ],
            'sisa_bayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'metode_bayar' => [
                 'type'       => 'VARCHAR',
                 'constraint' => '50',
                 'default'    => 'cash',
            ],
            'status_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['lunas', 'belum_lunas'],
                'default'    => 'belum_lunas',
            ],
            'status_produksi' => [
                'type'       => 'ENUM',
                'constraint' => ['queue', 'design', 'printing', 'finishing', 'done', 'taken'],
                'default'    => 'queue',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
