<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\TransactionItemModel;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    private ProductModel $productModel;
    private CategoryModel $categoryModel;
    private TransactionModel $transactionModel;
    private TransactionItemModel $transactionItemModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->transactionModel = new TransactionModel();
        $this->transactionItemModel = new TransactionItemModel();
    }

    public function pos()
    {
        $categoryId = $this->request->getGet('category_id');
        $keyword = trim((string) $this->request->getGet('q'));

        $builder = $this->productModel
            ->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.is_active', 1)
            ->where('products.stock >', 0);

        if ($categoryId !== null && $categoryId !== '') {
            $builder->where('products.category_id', (int) $categoryId);
        }

        if ($keyword !== '') {
            $builder->like('products.name', $keyword);
        }

        return view('transaction/pos', [
            'title'      => 'Kasir',
            'products'   => $builder->orderBy('products.name', 'ASC')->findAll(),
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'filters'    => [
                'category_id' => $categoryId,
                'q'           => $keyword,
            ],
        ]);
    }

    public function store()
    {
        $cart = json_decode((string) $this->request->getPost('cart_json'), true);
        $paymentAmount = (float) $this->request->getPost('payment_amount');
        $note = trim((string) $this->request->getPost('note'));

        if (! is_array($cart) || $cart === []) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Keranjang tidak boleh kosong.');
        }

        $items = [];
        $totalAmount = 0.0;

        foreach ($cart as $cartItem) {
            $productId = (int) ($cartItem['id'] ?? 0);
            $qty = (int) ($cartItem['qty'] ?? 0);

            if ($productId <= 0 || $qty <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Item keranjang tidak valid.');
            }

            $product = $this->productModel
                ->where('is_active', 1)
                ->find($productId);

            if (! $product) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Produk pada keranjang tidak ditemukan atau nonaktif.');
            }

            if ((int) $product->stock < $qty) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
            }

            $subtotal = (float) $product->price * $qty;
            $totalAmount += $subtotal;
            $items[] = [
                'product'  => $product,
                'qty'      => $qty,
                'subtotal' => $subtotal,
            ];
        }

        if ($paymentAmount < $totalAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pembayaran tidak boleh kurang dari total transaksi.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $invoiceNo = $this->generateInvoiceNo($db);
        $now = date('Y-m-d H:i:s');
        $this->transactionModel->insert([
            'invoice_no'     => $invoiceNo,
            'user_id'        => (int) session()->get('user_id'),
            'total_amount'   => $totalAmount,
            'payment_amount' => $paymentAmount,
            'change_amount'  => $paymentAmount - $totalAmount,
            'note'           => $note,
            'created_at'     => $now,
        ]);
        $transactionId = (int) $this->transactionModel->getInsertID();

        foreach ($items as $item) {
            $product = $item['product'];
            $qty = $item['qty'];

            $this->transactionItemModel->insert([
                'transaction_id' => $transactionId,
                'product_id'     => $product->id,
                'product_name'   => $product->name,
                'price'          => (float) $product->price,
                'qty'            => $qty,
                'subtotal'       => $item['subtotal'],
            ]);

            $this->productModel->update($product->id, [
                'stock' => (int) $product->stock - $qty,
            ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Transaksi gagal disimpan.');
        }

        $query = 'clear_cart=1';
        if ($this->request->getPost('print_receipt') === '1') {
            $query .= '&print=1';
        }

        return redirect()->to('/transaction/detail/' . $transactionId . '?' . $query)
            ->with('success', 'Transaksi berhasil disimpan. Invoice: ' . $invoiceNo);
    }

    public function history()
    {
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-d');
        $dateTo = $this->request->getGet('date_to') ?: date('Y-m-d');

        $builder = $this->transactionModel
            ->select('transactions.*, users.name AS cashier_name')
            ->join('users', 'users.id = transactions.user_id')
            ->where('DATE(transactions.created_at) >=', $dateFrom)
            ->where('DATE(transactions.created_at) <=', $dateTo)
            ->orderBy('transactions.created_at', 'DESC');

        if (session()->get('user_role') !== 'admin') {
            $builder->where('transactions.user_id', (int) session()->get('user_id'));
        }

        return view('transaction/history', [
            'title'        => 'Riwayat Transaksi',
            'transactions' => $builder->paginate(15, 'transactions'),
            'pager'        => $this->transactionModel->pager,
            'filters'      => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ],
        ]);
    }

    public function detail($id)
    {
        $transaction = $this->transactionModel
            ->select('transactions.*, users.name AS cashier_name')
            ->join('users', 'users.id = transactions.user_id')
            ->find((int) $id);

        if (! $transaction) {
            return redirect()->to('/transaction')->with('error', 'Transaksi tidak ditemukan.');
        }

        if (session()->get('user_role') !== 'admin' && (int) $transaction->user_id !== (int) session()->get('user_id')) {
            return redirect()->to('/transaction')->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('transaction/detail', [
            'title'       => 'Detail Transaksi',
            'transaction' => $transaction,
            'items'       => $this->transactionItemModel->where('transaction_id', $transaction->id)->findAll(),
        ]);
    }

    private function generateInvoiceNo($db): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $count = $db->table('transactions')
            ->like('invoice_no', $prefix, 'after')
            ->countAllResults();

        do {
            $count++;
            $invoiceNo = $prefix . str_pad((string) $count, 3, '0', STR_PAD_LEFT);
            $exists = $db->table('transactions')->where('invoice_no', $invoiceNo)->countAllResults() > 0;
        } while ($exists);

        return $invoiceNo;
    }
}
