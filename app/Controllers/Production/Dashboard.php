<?php

namespace App\Controllers\Production;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        
        // Security Check
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'production'])) {
            header('Location: /');
            exit;
        }
    }

    public function index()
    {
        // Fetch orders that are NOT 'done' or 'taken' (completed)
        // Ordered by deadline (urgent first), then created_at
        $orders = $this->transactionModel
            ->whereNotIn('status_produksi', ['done', 'taken'])
            ->orderBy('deadline', 'ASC') // NULLs might be last or first depending on DB, usually we want urgent first.
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Fetch details for each order
        $detailModel = new \App\Models\TransactionDetailModel();
        foreach ($orders as &$order) {
            $order['items'] = $detailModel
                ->select('transaction_details.*, products.nama_barang')
                ->join('products', 'products.id = transaction_details.product_id', 'left')
                ->where('transaction_id', $order['id'])
                ->findAll();
            
            // Debug Log
            if (empty($order['items'])) {
                log_message('error', 'No items found for Order ID: ' . $order['id']);
            } else {
                log_message('error', 'Items found for Order ID: ' . $order['id'] . ' - Count: ' . count($order['items']));
                log_message('error', 'Item 0 Project: ' . ($order['items'][0]['nama_project'] ?? 'NULL'));
                log_message('error', 'Item 0 Product: ' . ($order['items'][0]['nama_barang'] ?? 'NULL'));
            }
        }

        $data['orders'] = $orders;

        return view('production/dashboard', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['queue', 'design', 'printing', 'finishing', 'done', 'taken'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $this->transactionModel->update($id, ['status_produksi' => $status]);
        
        return $this->response->setJSON(['success' => true]);
    }
}
