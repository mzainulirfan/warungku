<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Login',
        ]);
    }

    public function processLogin()
    {
        $rules = [
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (! $user || (int) $user->is_active !== 1 || ! password_verify((string) $this->request->getPost('password'), $user->password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password tidak valid.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'logged_in' => true,
        ]);

        $redirectUrl = session()->get('redirect_url') ?: '/dashboard';
        session()->remove('redirect_url');

        return redirect()->to($redirectUrl)->with('success', 'Berhasil login.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}
