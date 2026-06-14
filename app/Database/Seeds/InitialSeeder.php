<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('users')->where('email', 'admin@warung.com')->countAllResults() === 0) {
            $this->db->table('users')->insert([
                'name'       => 'Administrator',
                'email'      => 'admin@warung.com',
                'password'   => password_hash('admin1234', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $settings = [
            'store_name'      => 'Warung Sederhana',
            'store_address'   => 'Jl. Contoh No. 1',
            'store_phone'     => '08123456789',
            'currency_symbol' => 'Rp',
        ];

        foreach ($settings as $key => $value) {
            if ($this->db->table('settings')->where('key', $key)->countAllResults() === 0) {
                $this->db->table('settings')->insert([
                    'key'        => $key,
                    'value'      => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        foreach (['Makanan', 'Minuman', 'Snack'] as $name) {
            if ($this->db->table('categories')->where('name', $name)->countAllResults() === 0) {
                $this->db->table('categories')->insert([
                    'name'       => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
