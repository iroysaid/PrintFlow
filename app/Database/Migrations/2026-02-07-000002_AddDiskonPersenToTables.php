<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonPersenToTables extends Migration
{
    public function up()
    {
        // Add diskon_persen to transaction_details
        $this->forge->addColumn('transaction_details', [
            'diskon_persen' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
                'after'      => 'subtotal', // Or wherever logical
            ],
        ]);

        // Add diskon_persen to transactions
        $this->forge->addColumn('transactions', [
            'diskon_persen' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
                'after'      => 'diskon',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction_details', 'diskon_persen');
        $this->forge->dropColumn('transactions', 'diskon_persen');
    }
}
