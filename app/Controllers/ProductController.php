<?php

namespace App\Controllers;

class ProductController extends BaseController
{
    public function index()
    {
        return $this->placeholder('Produk');
    }

    public function create()
    {
        return $this->placeholder('Tambah Produk');
    }

    public function store()
    {
        return redirect()->to('/product')->with('error', 'CRUD produk belum tersedia. Implementasi ada di Fase 3.');
    }

    public function edit($id)
    {
        return $this->placeholder('Edit Produk #' . $id);
    }

    public function update($id)
    {
        return redirect()->to('/product')->with('error', 'CRUD produk belum tersedia. Implementasi ada di Fase 3.');
    }

    public function delete($id)
    {
        return redirect()->to('/product')->with('error', 'CRUD produk belum tersedia. Implementasi ada di Fase 3.');
    }

    public function toggle($id)
    {
        return redirect()->to('/product')->with('error', 'CRUD produk belum tersedia. Implementasi ada di Fase 3.');
    }

    private function placeholder(string $title)
    {
        return view('transaction/placeholder', [
            'title'   => $title,
            'heading' => $title,
            'message' => 'Halaman produk akan diimplementasikan pada Fase 3.',
        ]);
    }
}
