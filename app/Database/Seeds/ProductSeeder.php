<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_barang' => 'MMT280',
                'nama_barang' => 'Banner MMT 280gr (Outdoor)',
                'jenis_harga' => 'meter',
                'harga_dasar' => 25000.00,
                'stok'        => 0, // Service item
                'gambar'      => 'banner-mmt.jpg',
            ],
            [
                'kode_barang' => 'STCKA3',
                'nama_barang' => 'Sticker Cromo A3+',
                'jenis_harga' => 'unit',
                'harga_dasar' => 5000.00,
                'stok'        => 500, // Physical stock
                'gambar'      => 'sticker-a3.jpg',
            ],
            [
                'kode_barang' => 'XBANN',
                'nama_barang' => 'X-Banner Stand + Print',
                'jenis_harga' => 'unit',
                'harga_dasar' => 75000.00,
                'stok'        => 20,
                'gambar'      => 'x-banner.jpg',
            ],
            [
                'kode_barang' => 'ONEWAY',
                'nama_barang' => 'Sticker One Way Vision',
                'jenis_harga' => 'meter',
                'harga_dasar' => 45000.00,
                'stok'        => 0,
                'gambar'      => 'oneway.jpg',
            ],
            [
                'kode_barang' => 'KNAMA',
                'nama_barang' => 'Kartu Nama (Box)',
                'jenis_harga' => 'unit',
                'harga_dasar' => 35000.00,
                'stok'        => 100,
                'gambar'      => 'kartu-nama.jpg',
            ],
        ];

        $db = \Config\Database::connect();
        // Clear table first for clean seed? Optional. Let's truncate for dev.
        $this->db->table('products')->truncate();
        
        $this->db->table('products')->insertBatch($data);
    }
}
