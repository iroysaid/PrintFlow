<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteContentTable extends Migration
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
            'section' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'index_num' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'content' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('site_content');

        // Seed Initial Data for 3 Promo Slots
        $db = \Config\Database::connect();
        $builder = $db->table('site_content');
        $data = [
            [
                'section' => 'promo',
                'index_num' => 1,
                'title' => 'Promo A',
                'content' => 'Deskripsi singkat promo 1.',
                'image' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section' => 'promo',
                'index_num' => 2,
                'title' => 'Promo B',
                'content' => 'Deskripsi singkat promo 2.',
                'image' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section' => 'promo',
                'index_num' => 3,
                'title' => 'Promo C',
                'content' => 'Deskripsi singkat promo 3.',
                'image' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $builder->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('site_content');
    }
}
