<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class Pos extends BaseController
{
    private $productModel;
    private $customerModel;
    private $transactionModel;
    private $transactionDetailModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->customerModel = new CustomerModel();
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }

    public function index()
    {
        return view('pos/index');
    }

    public function history()
    {
        $transactions = $this->transactionModel->getRecent(20);
        return view('pos/history', ['transactions' => $transactions]);
    }

    public function searchProduct()
    {
        $keyword = $this->request->getGet('term');
        $products = $this->productModel->searchWithPaging($keyword, 20);
        return $this->response->setJSON($products);
    }

    public function searchCustomer()
    {
        $term = $this->request->getGet('term');
        if (empty($term)) {
            return $this->response->setJSON([]);
        }

        $customers = $this->customerModel
            ->like('no_hp', $term)
            ->orLike('nama_customer', $term)
            ->limit(10)
            ->findAll();

        return $this->response->setJSON($customers);
    }

    public function checkCustomer()
    {
        $phone = $this->request->getGet('phone');
        $customer = $this->customerModel->where('no_hp', $phone)->first();
        
        if ($customer) {
            return $this->response->setJSON(['status' => 'found', 'data' => $customer]);
        } else {
            return $this->response->setJSON(['status' => 'not_found']);
        }
    }

    public function getCustomerHistory()
    {
        $phone = $this->request->getGet('phone');
        if (empty($phone)) {
            return $this->response->setJSON([]);
        }

        // Fetch last 10 transactions for this phone number
        // Note: We are trusting the phone number stored in transactions table directly
        $history = $this->transactionModel
            ->where('customer_phone', $phone)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();
            
        return $this->response->setJSON($history);
    }

    public function saveTransaction()
    {
        $json = $this->request->getJSON();
        
        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid Request']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Validation & Sanitization
            $phone = preg_replace('/[^0-9]/', '', $json->customer->no_hp ?? '');
            $name  = htmlspecialchars(strip_tags($json->customer->nama_customer ?? 'Guest'));
            
            // Strict Validation
            if (empty($json->items)) {
                return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Keranjang Belanja masih kosong!']);
            }
            if (empty($name) || $name === 'Guest' || empty($phone)) {
                 return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Data Pelanggan (Nama & No HP) Wajib Diisi!']);
            }
            if (strlen($phone) < 10) {
                return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Nomor HP harus angka & minimal 10 digit!']);
            }

            // Handle Customer
            $customerId = null;
            if (!empty($phone)) {
                $existingCustomer = $this->customerModel->where('no_hp', $phone)->first();
                if ($existingCustomer) {
                    $customerId = $existingCustomer['id'];
                    // Update name if changed? Optional. Let's keep existing name or update it.
                    // $this->customerModel->update($customerId, ['nama_customer' => $name]);
                } else {
                    $this->customerModel->insert([
                        'no_hp'         => $phone,
                        'nama_customer' => $name,
                    ]);
                    $customerId = $this->customerModel->getInsertID();
                }
            }

            // 2. Transaction Header Data
            $datePart = date('Ymd');
            $invoiceNo = 'INV/' . $datePart . '/' .  str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Completion Date Calculation
            $estimasi = $json->estimasi_hari ?? 1;
            $tglSelesai = date('Y-m-d', strtotime("+$estimasi days"));

            // Logic Status Bayar
            // Calculate Remainder safely
            $grandTotal = $json->grand_total;
            $nominalBayar = $json->nominal_bayar;
            $remainder = $grandTotal - $nominalBayar;
            
            // Tolerance of small float diff or simple logic
            $statusBayar = ($remainder <= 0) ? 'lunas' : 'belum_lunas';

            $transactionData = [
                'no_invoice'      => $invoiceNo,
                'customer_id'     => $customerId, // We have the ID now if we want to use it
                'customer_name'   => $name,
                'customer_phone'  => $phone,
                'tgl_masuk'       => date('Y-m-d H:i:s'),
                'estimasi_hari'   => $estimasi,
                'tgl_selesai'     => $tglSelesai,
                'total_asli'      => $json->total_asli ?? 0,
                'diskon'          => $json->diskon ?? 0,
                'grand_total'     => $grandTotal,
                'nominal_bayar'   => $nominalBayar,
                'sisa_bayar'      => ($remainder > 0) ? $remainder : 0,
                'metode_bayar'    => $json->metode_bayar ?? 'cash',
                'status_bayar'    => $statusBayar,
                'status_produksi' => 'queue',
                'user_id'         => session()->get('id'),
            ];

            $this->transactionModel->insert($transactionData);
            $transactionId = $this->transactionModel->getInsertID();

            // 3. Handle Details (The Core Printing Logic)
            foreach ($json->items as $item) {
                // Verify Product Price Logic Server Side
                $product = $this->productModel->find($item->id);
                if (!$product) continue;

                $panjang = $item->panjang ?? 1;
                $lebar   = $item->lebar ?? 1;
                $qty     = $item->qty;
                
                $calculatedSubtotal = 0;

                if ($product['jenis_harga'] == 'meter') {
                    // Meter Calculation: (L x W x Price) * Qty
                    $area = $panjang * $lebar;
                    // Rounding logic for meters? Usually minimum 1 meter? Let's assume standard math for now.
                    if ($area < 1) $area = 1; // Optional business rule: Min 1 meter? Let's stick to raw calc first or assume Prompt implied standard area.
                    // Actually prompt says: Formula: (Panjang * Lebar * Price) * Qty.
                    $calculatedSubtotal = ($panjang * $lebar * $product['harga_dasar']) * $qty;
                } else {
                    // Unit Calculation
                    $calculatedSubtotal = $product['harga_dasar'] * $qty;
                }
                
                // Use client subtotal or server? Server is safer.
                // But let's trust client inputs for P/L/Qty and recalculate cost.
                
                $this->transactionDetailModel->insert([
                    'transaction_id'    => $transactionId,
                    'product_id'        => $item->id,
                    'panjang'           => $panjang,
                    'lebar'             => $lebar,
                    'qty'               => $qty,
                    'catatan_finishing' => $item->catatan_finishing ?? '',
                    'link_file'         => $item->link_file ?? '',
                    'subtotal'          => $calculatedSubtotal,
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                 return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Transaction Failed']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'invoice_no' => $invoiceNo,
                'transaction_id' => $transactionId
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function printInvoice($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $items = $this->transactionDetailModel
            ->select('transaction_details.*, products.nama_barang, products.jenis_harga, products.harga_dasar') // Select jenis_harga
            ->join('products', 'products.id = transaction_details.product_id')
            ->where('transaction_id', $id)
            ->findAll();

        return view('pos/print_invoice', [
            'transaction' => $transaction,
            'items'       => $items,
        ]);
    }
}
