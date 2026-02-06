<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionDetailsTable extends Migration
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
            'transaction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'panjang' => [
                 'type'       => 'DECIMAL',
                 'constraint' => '10,2',
                 'default'    => 1.00,
            ],
            'lebar' => [
                 'type'       => 'DECIMAL',
                 'constraint' => '10,2',
                 'default'    => 1.00,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'catatan_finishing' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'link_file' => [
                 'type'       => 'VARCHAR',
                 'constraint' => '255',
                 'null'       => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transaction_details');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_details');
    }
}
