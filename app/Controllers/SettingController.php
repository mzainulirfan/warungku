<?php

namespace App\Controllers;

use App\Models\SettingModel;

class SettingController extends BaseController
{
    private const SETTING_KEYS = [
        'store_name',
        'store_address',
        'store_phone',
        'currency_symbol',
    ];

    private SettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        return view('setting/index', [
            'title'    => 'Setting',
            'settings' => $this->settingsMap(),
        ]);
    }

    public function update()
    {
        $rules = [
            'store_name' => [
                'label'  => 'Nama Toko',
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required'   => 'Nama toko wajib diisi.',
                    'max_length' => 'Nama toko maksimal 100 karakter.',
                ],
            ],
            'store_address' => [
                'label'  => 'Alamat Toko',
                'rules'  => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Alamat toko maksimal 255 karakter.',
                ],
            ],
            'store_phone' => [
                'label'  => 'Nomor Telepon',
                'rules'  => 'permit_empty|max_length[20]',
                'errors' => [
                    'max_length' => 'Nomor telepon maksimal 20 karakter.',
                ],
            ],
            'currency_symbol' => [
                'label'  => 'Simbol Mata Uang',
                'rules'  => 'required|max_length[10]',
                'errors' => [
                    'required'   => 'Simbol mata uang wajib diisi.',
                    'max_length' => 'Simbol mata uang maksimal 10 karakter.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        foreach (self::SETTING_KEYS as $key) {
            $this->saveSetting($key, trim((string) $this->request->getPost($key)));
        }

        return redirect()->to('/setting')->with('success', 'Setting berhasil diperbarui.');
    }

    private function settingsMap(): array
    {
        $rows = $this->settingModel->findAll();
        $settings = [];

        foreach ($rows as $row) {
            $settings[$row->key] = $row->value;
        }

        return array_merge([
            'store_name'      => 'Warung Sederhana',
            'store_address'   => '',
            'store_phone'     => '',
            'currency_symbol' => 'Rp',
        ], $settings);
    }

    private function saveSetting(string $key, string $value): void
    {
        $setting = $this->settingModel->where('key', $key)->first();

        if ($setting) {
            $this->settingModel->update($setting->id, [
                'value' => $value,
            ]);
            return;
        }

        $this->settingModel->insert([
            'key'   => $key,
            'value' => $value,
        ]);
    }
}
