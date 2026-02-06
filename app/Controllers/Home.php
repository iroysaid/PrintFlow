<?php

namespace App\Controllers;

use App\Models\WebPostModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $webPostModel = new WebPostModel();
        $productModel = new ProductModel();

        // Fetch Data
        $posts = $webPostModel->getActivePosts();
        
        // Fetch Pricelist (Limit 12 for showcase)
        $products = $productModel->orderBy('nama_barang', 'ASC')->findAll(12);

        return view('landing_page', [
            'posts'    => $posts,
            'products' => $products
        ]);
    }
}
