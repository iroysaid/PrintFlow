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
        $sort = $this->request->getGet('sort');
        $productModel = $this->productModel;

        if ($sort == 'name_asc') {
            $productModel->orderBy('nama_barang', 'ASC');
        } elseif ($sort == 'popular') {
            // Join with transaction_details to count sales
            $productModel->select('products.*, COUNT(transaction_details.id) as sales_count')
                         ->join('transaction_details', 'transaction_details.product_id = products.id', 'left')
                         ->groupBy('products.id')
                         ->orderBy('sales_count', 'DESC');
        } else {
            // Default: Newest
            $productModel->orderBy('products.id', 'DESC');
        }

        $data = [
            'products' => $productModel->findAll(),
            'currentSort' => $sort // Pass current sort to view for UI state
        ];
        return view('admin/products', $data);
    }

    public function create()
    {
        $validationRule = [
            'gambar' => [
                'label' => 'Image File',
                'rules' => 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,800]',
            ],
            'kode_barang' => 'required|is_unique[products.kode_barang]',
            'nama_barang' => 'required',
            'harga_dasar' => 'required|numeric',
            'stok'        => 'required|integer'
        ];

        if (! $this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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
        $validationRule = [
            'gambar' => [
                'label' => 'Image File',
                'rules' => 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,800]',
            ],
            'kode_barang' => "required|is_unique[products.kode_barang,id,$id]",
            'nama_barang' => 'required',
            'harga_dasar' => 'required|numeric',
            'stok'        => 'required|integer'
        ];

        if (! $this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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
