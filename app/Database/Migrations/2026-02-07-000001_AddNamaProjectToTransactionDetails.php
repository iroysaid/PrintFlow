<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNamaProjectToTransactionDetails extends Migration
{
    public function up()
    {
        $fields = [
            'nama_project' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'product_id', // Place it nicely after product_id
            ],
        ];
        $this->forge->addColumn('transaction_details', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction_details', 'nama_project');
    }
}
