<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;

class Debug extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        echo "<h1>Debug Database Data</h1>";
        
        // 1. Check Transactions
        echo "<h2>Transactions (Last 5)</h2>";
        $transModel = new TransactionModel();
        $transactions = $transModel->orderBy('id', 'DESC')->findAll(5);
        echo "<pre>"; print_r($transactions); echo "</pre>";
        
        if(empty($transactions)) {
            echo "No transactions found.<br>";
        } else {
            $lastId = $transactions[0]['id'];
            echo "<h3>Checking Details for Transaction ID: $lastId</h3>";
            
            // 2. Check Details Raw
            $detailModel = new TransactionDetailModel();
            $details = $detailModel->where('transaction_id', $lastId)->findAll();
            echo "<h4>Raw Details Content:</h4>";
            echo "<pre>"; print_r($details); echo "</pre>";
            
            if(!empty($details)) {
                $prodId = $details[0]['product_id'];
                echo "<h3>Checking Product ID: $prodId</h3>";
                
                // 3. Check Product
                $prodModel = new ProductModel();
                $product = $prodModel->find($prodId);
                echo "<h4>Product Content:</h4>";
                echo "<pre>"; print_r($product); echo "</pre>";
                
                // 4. Test Join
                echo "<h3>Test Join Query</h3>";
                $joinTest = $detailModel
                    ->select('transaction_details.*, products.nama_barang')
                    ->join('products', 'products.id = transaction_details.product_id', 'left')
                    ->where('transaction_id', $lastId)
                    ->findAll();
                 echo "<h4>Join Result:</h4>";
                 echo "<pre>"; print_r($joinTest); echo "</pre>";
            }
        }
        
        // 5. Check All Details Table
        echo "<h2>All Transaction Details (Limit 10)</h2>";
         $query = $db->query("SELECT * FROM transaction_details LIMIT 10");
         $results = $query->getResultArray();
         echo "<pre>"; print_r($results); echo "</pre>";
    }
}
