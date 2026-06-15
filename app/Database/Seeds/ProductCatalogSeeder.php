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

        $productNames = [
            'Nasi Goreng Kampung',
            'Nasi Ayam Geprek',
            'Nasi Telur Balado',
            'Nasi Pecel',
            'Soto Ayam',
            'Es Teh Manis',
            'Es Jeruk',
            'Air Mineral 600ml',
            'Susu Kotak Cokelat',
            'Minuman Soda Kaleng',
            'Teh Panas',
            'Kopi Hitam',
            'Kopi Susu',
            'Wedang Jahe',
            'Cokelat Panas',
            'Keripik Singkong',
            'Keripik Kentang',
            'Kacang Atom',
            'Biskuit Cokelat',
            'Wafer Vanilla',
            'Roti Tawar',
            'Roti Cokelat',
            'Donat Gula',
            'Brownies Potong',
            'Kue Lapis',
            'Beras 1kg',
            'Gula Pasir 1kg',
            'Minyak Goreng 1L',
            'Telur Ayam 1kg',
            'Tepung Terigu 1kg',
            'Garam Dapur',
            'Merica Bubuk',
            'Ketumbar Bubuk',
            'Kecap Manis Sachet',
            'Saus Sambal Botol',
            'Kopi Sachet Original',
            'Kopi Sachet Susu',
            'Teh Celup Isi 25',
            'Krimer Sachet',
            'Gula Aren Sachet',
            'Mie Goreng Instan',
            'Mie Kuah Soto',
            'Mie Kari Ayam',
            'Mie Pedas',
            'Bihun Instan',
            'Sabun Mandi Batang',
            'Sampo Sachet',
            'Pasta Gigi Kecil',
            'Tisu Gulung',
            'Deterjen Sachet',
        ];

        $existingProducts = [];
        foreach ($this->db->table('products')->select('name')->get()->getResultArray() as $product) {
            $existingProducts[$product['name']] = true;
        }

        $products = [];
        for ($index = 1; $index <= 1000; $index++) {
            $categoryName = $categories[($index - 1) % count($categories)];
            $baseName = $productNames[($index - 1) % count($productNames)];
            $name = 'Produk Demo ' . str_pad((string) $index, 4, '0', STR_PAD_LEFT) . ' - ' . $baseName;
            $price = 1000 + (($index % 60) * 1000);
            $stock = 10 + ($index % 90);

            if (isset($existingProducts[$name])) {
                continue;
            }

            $products[] = [
                'category_id' => $categoryIds[$categoryName],
                'name'        => $name,
                'price'       => $price,
                'stock'       => $stock,
                'image'       => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        foreach (array_chunk($products, 100) as $chunk) {
            $this->db->table('products')->insertBatch($chunk);
        }

        foreach ($this->db->table('products')
            ->select('id')
            ->like('name', 'Produk Demo ', 'after')
            ->where('barcode', null)
            ->get()
            ->getResult() as $product) {
            $this->db->table('products')
                ->where('id', $product->id)
                ->update([
                    'barcode'    => 'WRG-' . str_pad((string) $product->id, 8, '0', STR_PAD_LEFT),
                    'updated_at' => $now,
                ]);
        }
    }
}
