<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBarcodeToProductsTable extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('barcode', 'products')) {
            $this->forge->addColumn('products', [
                'barcode' => ['type' => 'TEXT', 'null' => true],
            ]);
        }

        $this->db->query("UPDATE products SET barcode = 'WRG-' || printf('%08d', id) WHERE barcode IS NULL OR barcode = ''");
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS products_barcode_unique ON products (barcode)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX IF EXISTS products_barcode_unique');
        $this->forge->dropColumn('products', 'barcode');
    }

}
