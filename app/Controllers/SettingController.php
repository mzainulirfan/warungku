<?php

namespace App\Controllers;

class SettingController extends BaseController
{
    public function index()
    {
        return view('transaction/placeholder', [
            'title'   => 'Setting',
            'heading' => 'Setting',
            'message' => 'Halaman setting akan diimplementasikan pada Fase 3.',
        ]);
    }

    public function update()
    {
        return redirect()->to('/setting')->with('error', 'Setting belum tersedia. Implementasi ada di Fase 3.');
    }
}
