<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'category_id' => ['type' => 'INTEGER', 'null' => true],
            'name'        => ['type' => 'TEXT', 'null' => false],
            'price'       => ['type' => 'REAL', 'null' => false, 'default' => 0],
            'stock'       => ['type' => 'INTEGER', 'null' => false, 'default' => 0],
            'image'       => ['type' => 'TEXT', 'null' => true],
            'is_active'   => ['type' => 'INTEGER', 'null' => false, 'default' => 1],
            'created_at'  => ['type' => 'TEXT', 'null' => true],
            'updated_at'  => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
