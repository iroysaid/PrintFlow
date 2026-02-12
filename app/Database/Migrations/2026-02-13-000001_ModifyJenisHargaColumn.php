<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyJenisHargaColumn extends Migration
{
    public function up()
    {
        $fields = [
            'jenis_harga' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'unit',
            ],
        ];
        $this->forge->modifyColumn('products', $fields);
    }

    public function down()
    {
        $fields = [
            'jenis_harga' => [
                'type'       => 'ENUM',
                'constraint' => ['unit', 'meter'],
                'default'    => 'unit',
            ],
        ];
        $this->forge->modifyColumn('products', $fields);
    }
}
