<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
            'transaction_id' => ['type' => 'INTEGER', 'null' => false],
            'product_id'     => ['type' => 'INTEGER', 'null' => false],
            'product_name'   => ['type' => 'TEXT', 'null' => false],
            'price'          => ['type' => 'REAL', 'null' => false],
            'qty'            => ['type' => 'INTEGER', 'null' => false, 'default' => 1],
            'subtotal'       => ['type' => 'REAL', 'null' => false, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('transaction_items');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_items');
    }
}
