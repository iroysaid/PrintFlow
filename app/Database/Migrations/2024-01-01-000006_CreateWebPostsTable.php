<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWebPostsTable extends Migration
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
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['news', 'promo'],
                'default'    => 'news',
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
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
        $this->forge->createTable('web_posts');
    }

    public function down()
    {
        $this->forge->dropTable('web_posts');
    }
}
