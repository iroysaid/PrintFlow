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
        $search = $this->request->getGet('search');
        $tanggal = $this->request->getGet('tanggal');

        $query = $this->transactionModel->orderBy('created_at', 'DESC');

        if (!empty($search)) {
            $query->groupStart()
                ->like('no_invoice', $search)
                ->orLike('customer_name', $search)
                ->orLike('customer_phone', $search)
                ->groupEnd();
        }

        if (!empty($tanggal)) {
            $query->where("DATE(tgl_masuk)", $tanggal);
        }

        $transactions = $query->findAll(50);

        return view('pos/history', [
            'transactions' => $transactions,
            'search' => $search,
            'tanggal' => $tanggal
        ]);
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
            // New Format: WISE/4448(ddmmyy)/Seq
            $prefix = 'WISE/4448' . date('dmy') . '/';
            
            // Get Sequence (Global - No Reset)
            $count = $this->transactionModel->countAllResults();
            $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            
            $invoiceNo = $prefix . $seq;
            
            // Completion Date Calculation
            $estimasi = $json->estimasi_hari ?? 1;
            $tglSelesai = date('Y-m-d', strtotime("+$estimasi days"));

            // Logic Status Bayar
            // Calculate Remainder safely
            // Calculate Global Discount based on Percent
            $globalDiscPercent = $json->diskon_persen ?? 0;
            $globalDiscount = $json->total_asli * ($globalDiscPercent / 100);
            $grandTotal = $json->total_asli - $globalDiscount;

            // Recalculate Remainder
            $nominalBayar = $json->nominal_bayar;
            $remainder = $grandTotal - $nominalBayar;
            $statusBayar = ($remainder <= 0) ? 'lunas' : 'belum_lunas'; // Simple check

            $transactionData = [
                'no_invoice'      => $invoiceNo,
                'customer_id'     => $customerId,
                'customer_name'   => $name,
                'customer_phone'  => $phone,
                'tgl_masuk'       => $json->created_at ?? date('Y-m-d H:i:s'), 
                'estimasi_hari'   => $estimasi,
                'tgl_selesai'     => $tglSelesai,
                'total_asli'      => $json->total_asli ?? 0,
                'diskon_persen'   => $globalDiscPercent,
                'diskon'          => $globalDiscount, 
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

                // Server-Side Strict Calculation
                if ($product['jenis_harga'] == 'meter') {
                    $area = $panjang * $lebar;
                    if ($area < 1) $area = 1; // Min 1 meter rule assumption? Or exact? Using standard exact.
                    $gross = ($panjang * $lebar * $product['harga_dasar']) * $qty;
                } else {
                    $gross = $product['harga_dasar'] * $qty;
                }
                
                // Item Discount
                $itemDiscPercent = $item->diskon_persen ?? 0;
                $discAmount = $gross * ($itemDiscPercent / 100);
                $calculatedSubtotal = $gross - $discAmount;
                
                $this->transactionDetailModel->insert([
                    'transaction_id'    => $transactionId,
                    'product_id'        => $item->id,
                    'nama_project'      => $item->nama_project ?? null,
                    'panjang'           => $panjang,
                    'lebar'             => $lebar,
                    'qty'               => $qty,
                    'catatan_finishing' => $item->catatan_finishing ?? '',
                    'link_file'         => $item->link_file ?? '',
                    'diskon_persen'     => $itemDiscPercent,
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
    public function deleteTransaction($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete details first (cascade usually handles this but manual is safer)
            $this->transactionDetailModel->where('transaction_id', $id)->delete();
            
            // Delete header
            $this->transactionModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                 return redirect()->to('/pos/history')->with('error', 'Gagal menghapus transaksi.');
            }

            return redirect()->to('/pos/history')->with('success', 'Transaksi berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->to('/pos/history')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function editTransaction($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            return redirect()->to('/pos/history')->with('error', 'Transaksi tidak ditemukan.');
        }

        $items = $this->transactionDetailModel
            ->select('transaction_details.*, products.nama_barang, products.jenis_harga')
            ->join('products', 'products.id = transaction_details.product_id')
            ->where('transaction_id', $id)
            ->findAll();

        return view('pos/edit_transaction', [
            'transaction' => $transaction,
            'items'       => $items,
        ]);
    }

    public function updateTransaction($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            return redirect()->to('/pos/history')->with('error', 'Transaksi tidak ditemukan.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_customer' => 'required',
            'no_hp'         => 'required|numeric|min_length(10)',
            'status_bayar'  => 'required|in_list[lunas,belum_lunas]',
            'status_produksi' => 'required|in_list[queue,process,done,taken]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaCustomer  = $this->request->getPost('nama_customer');
        $noHp          = $this->request->getPost('no_hp');
        $statusBayar   = $this->request->getPost('status_bayar');
        $statusProduksi = $this->request->getPost('status_produksi');
        $nominalBayar  = str_replace('.', '', $this->request->getPost('nominal_bayar')); // Remove formatting if any
        
        // Recalculate Logic
        $grandTotal = $transaction['grand_total'];
        $sisaBayar  = ($grandTotal - $nominalBayar > 0) ? ($grandTotal - $nominalBayar) : 0;
        
        // Override status bayar if manually set, but logic check is good too.
        // If user sets 'lunas' but Sisa > 0, should we force logic?
        // Let's trust the Manual Selection for Status Bayar but update numbers.
        
        $this->transactionModel->update($id, [
            'customer_name'   => $namaCustomer,
            'customer_phone'  => $noHp,
            'status_bayar'    => $statusBayar,
            'status_produksi' => $statusProduksi,
            'nominal_bayar'   => $nominalBayar,
            'sisa_bayar'      => $sisaBayar,
        ]);

        // Update Customer Record too if name changed? Optional.
        // $this->customerModel->update($transaction['customer_id'], ['nama_customer' => $namaCustomer, 'no_hp' => $noHp]);

        return redirect()->to('/pos/history')->with('success', 'Transaksi berhasil diperbarui.');
    }
}
