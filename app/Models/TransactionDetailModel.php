<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionDetailModel extends Model
{
    protected $table            = 'transaction_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'transaction_id', 'product_id', 'panjang', 'lebar', 'qty', 
        'catatan_finishing', 'link_file', 'subtotal'
    ];
}
