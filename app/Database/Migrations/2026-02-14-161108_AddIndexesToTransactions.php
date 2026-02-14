<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesToTransactions extends Migration
{
    public function up()
    {
        $this->db->query("CREATE INDEX idx_status_produksi ON transactions (status_produksi)");
        $this->db->query("CREATE INDEX idx_created_at ON transactions (created_at)");
        $this->db->query("CREATE INDEX idx_customer ON transactions (customer_name)");
    }

    public function down()
    {
        $this->db->query("DROP INDEX idx_status_produksi");
        $this->db->query("DROP INDEX idx_created_at");
        $this->db->query("DROP INDEX idx_customer");
    }
}
