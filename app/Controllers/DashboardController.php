<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $userId = session()->get('user_id');
        $isAdmin = session()->get('user_role') === 'admin';

        $salesBuilder = $db->table('transactions')
            ->select('COALESCE(SUM(total_amount), 0) AS total', false)
            ->where("DATE(created_at)", $today);

        $trxBuilder = $db->table('transactions')
            ->where("DATE(created_at)", $today);

        $lastBuilder = $db->table('transactions')
            ->select('transactions.*, users.name AS cashier_name')
            ->join('users', 'users.id = transactions.user_id')
            ->where("DATE(transactions.created_at)", $today)
            ->orderBy('transactions.created_at', 'DESC')
            ->limit(5);

        if (! $isAdmin) {
            $salesBuilder->where('user_id', $userId);
            $trxBuilder->where('user_id', $userId);
            $lastBuilder->where('transactions.user_id', $userId);
        }

        $data = [
            'title'              => 'Dashboard',
            'totalSales'         => (float) ($salesBuilder->get()->getRow()->total ?? 0),
            'totalTransactions'  => $trxBuilder->countAllResults(),
            'activeProducts'     => $isAdmin ? $db->table('products')->where('is_active', 1)->countAllResults() : null,
            'totalCategories'    => $isAdmin ? $db->table('categories')->countAllResults() : null,
            'latestTransactions' => $lastBuilder->get()->getResult(),
            'isAdmin'            => $isAdmin,
        ];

        return view('dashboard/index', $data);
    }
}
