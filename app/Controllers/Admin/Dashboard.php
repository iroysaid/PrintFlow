<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class Dashboard extends BaseController
{
    private $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // Analytics
        $incomeToday = $this->transactionModel
                            ->where('created_at >=', $today . ' 00:00:00')
                            ->selectSum('nominal_bayar')
                            ->first()['nominal_bayar'] ?? 0;
        
        $ordersToday = $this->transactionModel
                            ->where('created_at >=', $today . ' 00:00:00')
                            ->countAllResults();
        
        $onProgress = $this->transactionModel
                           ->whereIn('status_produksi', ['queue', 'design', 'printing', 'finishing'])
                           ->countAllResults();

        // Production Queue
        $queue = $this->transactionModel->getProductionQueue();

        return view('admin/dashboard', [
            'incomeToday' => $incomeToday,
            'ordersToday' => $ordersToday,
            'onProgress'  => $onProgress,
            'queue'       => $queue
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        if($status) {
            $this->transactionModel->update($id, ['status_produksi' => $status]);
        }
        return redirect()->back();
    }
}
