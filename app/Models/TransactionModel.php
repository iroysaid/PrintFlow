<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_invoice', 'customer_id', 'customer_name', 'customer_phone', 'tgl_masuk', 'estimasi_hari', 
        'tgl_selesai', 'total_asli', 'diskon', 'grand_total', 'nominal_bayar', 
        'sisa_bayar', 'metode_bayar', 'status_bayar', 'status_produksi', 'user_id',
        'order_notes', 'deadline'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getRecent($limit = 20)
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    public function getProductionQueue()
    {
        return $this->whereIn('status_produksi', ['queue', 'design', 'printing', 'finishing'])
                    ->orderBy('id', 'DESC') // Newest orders first
                    ->findAll();
    }
}
