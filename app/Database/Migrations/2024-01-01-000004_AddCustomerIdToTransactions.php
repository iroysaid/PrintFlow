<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerIdToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'no_invoice',
            ],
        ];
        $this->forge->addColumn('transactions', $fields);
        
        // Optional: Add Foreign Key if customers table exists
        // $this->forge->addForeignKey('customer_id', 'customers', 'id', 'SET NULL', 'CASCADE');
        // $this->forge->processIndexes('transactions'); 
        // Note: Simple column add is safer for now to avoid constraint errors on existing data
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'customer_id');
    }
}
