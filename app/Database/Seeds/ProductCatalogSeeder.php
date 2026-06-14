<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductCatalogSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $categories = [
            'Makanan Berat',
            'Minuman Dingin',
            'Minuman Panas',
            'Snack',
            'Roti dan Kue',
            'Sembako',
            'Bumbu Dapur',
            'Kopi dan Teh',
            'Mie Instan',
            'Perlengkapan Harian',
        ];

        $categoryIds = [];
        foreach ($categories as $name) {
            $existing = $this->db->table('categories')->where('name', $name)->get()->getRow();

            if (! $existing) {
                $this->db->table('categories')->insert([
                    'name'       => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $categoryIds[$name] = (int) $this->db->insertID();
                continue;
            }

            $categoryIds[$name] = (int) $existing->id;
        }

        $products = [
            ['Makanan Berat', 'Nasi Goreng Kampung', 15000, 25],
            ['Makanan Berat', 'Nasi Ayam Geprek', 18000, 20],
            ['Makanan Berat', 'Nasi Telur Balado', 13000, 18],
            ['Makanan Berat', 'Nasi Pecel', 14000, 16],
            ['Makanan Berat', 'Soto Ayam', 16000, 15],
            ['Minuman Dingin', 'Es Teh Manis', 5000, 40],
            ['Minuman Dingin', 'Es Jeruk', 7000, 35],
            ['Minuman Dingin', 'Air Mineral 600ml', 4000, 60],
            ['Minuman Dingin', 'Susu Kotak Cokelat', 6500, 32],
            ['Minuman Dingin', 'Minuman Soda Kaleng', 9000, 24],
            ['Minuman Panas', 'Teh Panas', 4000, 30],
            ['Minuman Panas', 'Kopi Hitam', 6000, 30],
            ['Minuman Panas', 'Kopi Susu', 8000, 28],
            ['Minuman Panas', 'Wedang Jahe', 9000, 20],
            ['Minuman Panas', 'Cokelat Panas', 10000, 18],
            ['Snack', 'Keripik Singkong', 8000, 45],
            ['Snack', 'Keripik Kentang', 10000, 35],
            ['Snack', 'Kacang Atom', 7000, 40],
            ['Snack', 'Biskuit Cokelat', 8500, 38],
            ['Snack', 'Wafer Vanilla', 7500, 34],
            ['Roti dan Kue', 'Roti Tawar', 14000, 22],
            ['Roti dan Kue', 'Roti Cokelat', 6000, 30],
            ['Roti dan Kue', 'Donat Gula', 5000, 28],
            ['Roti dan Kue', 'Brownies Potong', 9000, 20],
            ['Roti dan Kue', 'Kue Lapis', 4500, 25],
            ['Sembako', 'Beras 1kg', 14500, 50],
            ['Sembako', 'Gula Pasir 1kg', 16000, 45],
            ['Sembako', 'Minyak Goreng 1L', 17500, 42],
            ['Sembako', 'Telur Ayam 1kg', 29000, 30],
            ['Sembako', 'Tepung Terigu 1kg', 12000, 35],
            ['Bumbu Dapur', 'Garam Dapur', 4000, 60],
            ['Bumbu Dapur', 'Merica Bubuk', 5500, 45],
            ['Bumbu Dapur', 'Ketumbar Bubuk', 5000, 44],
            ['Bumbu Dapur', 'Kecap Manis Sachet', 2500, 80],
            ['Bumbu Dapur', 'Saus Sambal Botol', 12000, 25],
            ['Kopi dan Teh', 'Kopi Sachet Original', 2000, 100],
            ['Kopi dan Teh', 'Kopi Sachet Susu', 2500, 95],
            ['Kopi dan Teh', 'Teh Celup Isi 25', 9000, 30],
            ['Kopi dan Teh', 'Krimer Sachet', 1500, 80],
            ['Kopi dan Teh', 'Gula Aren Sachet', 3000, 55],
            ['Mie Instan', 'Mie Goreng Instan', 3500, 90],
            ['Mie Instan', 'Mie Kuah Soto', 3500, 85],
            ['Mie Instan', 'Mie Kari Ayam', 3500, 82],
            ['Mie Instan', 'Mie Pedas', 4000, 75],
            ['Mie Instan', 'Bihun Instan', 4500, 60],
            ['Perlengkapan Harian', 'Sabun Mandi Batang', 5000, 40],
            ['Perlengkapan Harian', 'Sampo Sachet', 1500, 90],
            ['Perlengkapan Harian', 'Pasta Gigi Kecil', 7000, 32],
            ['Perlengkapan Harian', 'Tisu Gulung', 6000, 36],
            ['Perlengkapan Harian', 'Deterjen Sachet', 2500, 70],
        ];

        foreach ($products as [$categoryName, $name, $price, $stock]) {
            if ($this->db->table('products')->where('name', $name)->countAllResults() > 0) {
                continue;
            }

            $this->db->table('products')->insert([
                'category_id' => $categoryIds[$categoryName],
                'name'        => $name,
                'price'       => $price,
                'stock'       => $stock,
                'image'       => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
