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
        $file = $this->request->getFile('gambar');
        $fileName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move('uploads/products', $fileName);
        }

        $data = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_harga' => $this->request->getPost('jenis_harga'),
            'harga_dasar' => $this->request->getPost('harga_dasar'),
            'stok'        => $this->request->getPost('stok') ?: 0,
            'gambar'      => $fileName,
        ];
        
        $this->productModel->insert($data);
        return redirect()->to('/admin/products')->with('success', 'Product Created');
    }

    public function update($id)
    {
        $product = $this->productModel->find($id);
        $file = $this->request->getFile('gambar');
        $fileName = $product['gambar'];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old file if exists
            if ($product['gambar'] && file_exists('uploads/products/' . $product['gambar'])) {
                unlink('uploads/products/' . $product['gambar']);
            }
            
            $fileName = $file->getRandomName();
            $file->move('uploads/products', $fileName);
        }

        $data = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_harga' => $this->request->getPost('jenis_harga'),
            'harga_dasar' => $this->request->getPost('harga_dasar'),
            'stok'        => $this->request->getPost('stok') ?: 0,
            'gambar'      => $fileName,
        ];

        $this->productModel->update($id, $data);
        return redirect()->to('/admin/products')->with('success', 'Product Updated');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);
        if ($product['gambar'] && file_exists('uploads/products/' . $product['gambar'])) {
            unlink('uploads/products/' . $product['gambar']);
        }
        
        $this->productModel->delete($id);
        return redirect()->back()->with('success', 'Product Deleted');
    }
}
