<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class Report extends BaseController
{
    private $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function weekend()
    {
        $data = [
            'reports' => $this->transactionModel->getWeekendReport()
        ];
        return view('report/index', $data);
    }
}
