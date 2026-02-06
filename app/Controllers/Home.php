<?php

namespace App\Controllers;

use App\Models\WebPostModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $siteContentModel = new \App\Models\SiteContentModel();
        $webPostModel = new WebPostModel();
        $productModel = new ProductModel();

        // Fetch Data
        $promos = $siteContentModel->where('section', 'promo')->orderBy('index_num', 'ASC')->findAll();
        $posts = $webPostModel->getActivePosts(); // Keep existing posts logic or replace? User said "update gambar... ada 3". I will pass both for now.
        
        // Fetch Pricelist (Limit 12 for showcase)
        $products = $productModel->orderBy('nama_barang', 'ASC')->findAll(12);

        return view('landing_page', [
            'promos'   => $promos,
            'posts'    => $posts,
            'products' => $products
        ]);
    }
}
