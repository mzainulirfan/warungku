<?php

namespace App\Controllers;

class TransactionController extends BaseController
{
    public function pos()
    {
        return view('transaction/placeholder', [
            'title'   => 'Kasir',
            'heading' => 'Kasir / POS',
            'message' => 'Halaman POS akan diimplementasikan pada Fase 4.',
        ]);
    }

    public function store()
    {
        return redirect()->to('/pos')->with('error', 'Penyimpanan transaksi belum tersedia. Implementasi ada di Fase 4.');
    }

    public function history()
    {
        return view('transaction/placeholder', [
            'title'   => 'Riwayat Transaksi',
            'heading' => 'Riwayat Transaksi',
            'message' => 'Riwayat transaksi akan diimplementasikan pada Fase 4.',
        ]);
    }

    public function detail($id)
    {
        return view('transaction/placeholder', [
            'title'   => 'Detail Transaksi',
            'heading' => 'Detail Transaksi #' . esc($id),
            'message' => 'Detail transaksi akan diimplementasikan pada Fase 4.',
        ]);
    }
}
