<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class Transaction extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $data = [
            'transactions' => $this->transactionModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/transactions', $data);
    }
}
