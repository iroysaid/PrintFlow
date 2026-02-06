<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class Dashboard extends BaseController
{
    private $siteContentModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->siteContentModel = new \App\Models\SiteContentModel();
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
        
        // Fetch Promos
        $promos = $this->siteContentModel->where('section', 'promo')->orderBy('index_num', 'ASC')->findAll();

        return view('admin/dashboard', [
            'incomeToday' => $incomeToday,
            'ordersToday' => $ordersToday,
            'onProgress'  => $onProgress,
            'queue'       => $queue,
            'promos'      => $promos
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

    public function updatePromo($id)
    {
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $file = $this->request->getFile('image');

        $data = [
            'title'   => $title,
            'content' => $content,
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/content', $newName);
            $data['image'] = $newName;
        }

        $this->siteContentModel->update($id, $data);

        return redirect()->back()->with('success', 'Promo updated successfully.');
    }
}
