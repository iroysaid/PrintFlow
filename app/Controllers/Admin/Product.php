<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Product extends BaseController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data = [
            'products' => $this->productModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/products', $data);
    }

    public function create()
    {
        $data = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_harga' => $this->request->getPost('jenis_harga'),
            'harga_dasar' => $this->request->getPost('harga_dasar'),
            'stok'        => $this->request->getPost('stok') ?: 0,
        ];
        
        // Handle Image Upload logic if needed, skipping for brevity in this step
        // or using default if not provided

        $this->productModel->insert($data);
        return redirect()->to('/admin/products')->with('success', 'Product Created');
    }

    public function update($id)
    {
        $data = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_harga' => $this->request->getPost('jenis_harga'),
            'harga_dasar' => $this->request->getPost('harga_dasar'),
            'stok'        => $this->request->getPost('stok') ?: 0,
        ];

        $this->productModel->update($id, $data);
        return redirect()->to('/admin/products')->with('success', 'Product Updated');
    }

    public function delete($id)
    {
        $this->productModel->delete($id);
        return redirect()->back()->with('success', 'Product Deleted');
    }
}
