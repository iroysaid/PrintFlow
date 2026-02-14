<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductionColumnsToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'order_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status_produksi' // MySQL only, ignored in SQLite but good for docs
            ],
            'deadline' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'order_notes'
            ],
        ];
        $this->forge->addColumn('transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', ['order_notes', 'deadline']);
    }
}
