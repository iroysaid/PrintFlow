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
        
        // Maybe fetch featured products or services logic? 
        // For now hardcode or use products table but products table is for POS items.
        // Let's just pass posts and simple view data.

        return view('landing_page', [
            'posts' => $posts
        ]);
    }
}
