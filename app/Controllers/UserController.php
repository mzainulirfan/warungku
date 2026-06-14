<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        return $this->placeholder('User Management');
    }

    public function create()
    {
        return $this->placeholder('Tambah User');
    }

    public function store()
    {
        return redirect()->to('/user')->with('error', 'User management belum tersedia. Implementasi ada di Fase 3.');
    }

    public function edit($id)
    {
        return $this->placeholder('Edit User #' . $id);
    }

    public function update($id)
    {
        return redirect()->to('/user')->with('error', 'User management belum tersedia. Implementasi ada di Fase 3.');
    }

    public function toggle($id)
    {
        return redirect()->to('/user')->with('error', 'User management belum tersedia. Implementasi ada di Fase 3.');
    }

    private function placeholder(string $title)
    {
        return view('transaction/placeholder', [
            'title'   => $title,
            'heading' => $title,
            'message' => 'User management akan diimplementasikan pada Fase 3.',
        ]);
    }
}
