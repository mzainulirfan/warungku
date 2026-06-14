<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('user/index', [
            'title' => 'User Management',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('user/create', [
            'title' => 'Tambah User',
            'user'  => null,
        ]);
    }

    public function store()
    {
        if (! $this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'name'      => trim((string) $this->request->getPost('name')),
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'password'  => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'      => (string) $this->request->getPost('role'),
            'is_active' => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to('/user')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find((int) $id);

        if (! $user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan.');
        }

        return view('user/edit', [
            'title' => 'Edit User',
            'user'  => $user,
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel->find((int) $id);

        if (! $user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan.');
        }

        if (! $this->validate($this->validationRules($user->id))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if ((int) session()->get('user_id') === (int) $user->id && (int) $this->request->getPost('is_active') === 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $data = [
            'name'      => trim((string) $this->request->getPost('name')),
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'role'      => (string) $this->request->getPost('role'),
            'is_active' => (int) $this->request->getPost('is_active'),
        ];

        if ((string) $this->request->getPost('password') !== '') {
            $data['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        $this->userModel->update($user->id, $data);

        if ((int) session()->get('user_id') === (int) $user->id) {
            session()->set([
                'user_name' => $data['name'],
                'user_role' => $data['role'],
            ]);
        }

        return redirect()->to('/user')->with('success', 'User berhasil diperbarui.');
    }

    public function toggle($id)
    {
        $user = $this->userModel->find((int) $id);

        if (! $user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan.');
        }

        if ((int) session()->get('user_id') === (int) $user->id && (int) $user->is_active === 1) {
            return redirect()->to('/user')->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $this->userModel->update($user->id, [
            'is_active' => (int) $user->is_active === 1 ? 0 : 1,
        ]);

        return redirect()->to('/user')->with('success', 'Status user berhasil diperbarui.');
    }

    private function validationRules(?int $userId = null): array
    {
        $emailRule = $userId === null
            ? 'required|valid_email|is_unique[users.email]'
            : "required|valid_email|is_unique[users.email,id,{$userId}]";

        $rules = [
            'name' => [
                'label'  => 'Nama',
                'rules'  => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama wajib diisi.',
                    'min_length' => 'Nama minimal 2 karakter.',
                    'max_length' => 'Nama maksimal 100 karakter.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => $emailRule,
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email sudah digunakan.',
                ],
            ],
            'role' => [
                'label'  => 'Role',
                'rules'  => 'required|in_list[admin,kasir]',
                'errors' => [
                    'required' => 'Role wajib dipilih.',
                    'in_list'  => 'Role yang dipilih tidak valid.',
                ],
            ],
            'is_active' => [
                'label'  => 'Status',
                'rules'  => 'required|in_list[0,1]',
                'errors' => [
                    'required' => 'Status wajib dipilih.',
                    'in_list'  => 'Status yang dipilih tidak valid.',
                ],
            ],
        ];

        $password = (string) $this->request->getPost('password');
        if ($userId === null || $password !== '') {
            $rules['password'] = [
                'label'  => 'Password',
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.',
                ],
            ];
            $rules['password_confirm'] = [
                'label'  => 'Konfirmasi Password',
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches'  => 'Konfirmasi password tidak sama.',
                ],
            ];
        }

        return $rules;
    }
}
