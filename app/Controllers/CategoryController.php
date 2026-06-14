<?php

namespace App\Controllers;

class CategoryController extends BaseController
{
    public function index()
    {
        return view('transaction/placeholder', [
            'title'   => 'Kategori',
            'heading' => 'Kategori',
            'message' => 'CRUD kategori akan diimplementasikan pada Fase 3.',
        ]);
    }

    public function store()
    {
        return redirect()->to('/category')->with('error', 'CRUD kategori belum tersedia. Implementasi ada di Fase 3.');
    }

    public function update($id)
    {
        return redirect()->to('/category')->with('error', 'CRUD kategori belum tersedia. Implementasi ada di Fase 3.');
    }

    public function delete($id)
    {
        return redirect()->to('/category')->with('error', 'CRUD kategori belum tersedia. Implementasi ada di Fase 3.');
    }
}
