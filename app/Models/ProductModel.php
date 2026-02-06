<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kode_barang', 'nama_barang', 'jenis_harga', 'harga_dasar', 'stok', 'gambar'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Search products by name or code
     */
    public function searchWithPaging($keyword, $perPage = 20)
    {
        return $this->groupStart()
                    ->like('nama_barang', $keyword)
                    ->orLike('kode_barang', $keyword)
                    ->groupEnd()
                    ->orderBy('nama_barang', 'ASC')
                    ->findAll($perPage);
    }
}
