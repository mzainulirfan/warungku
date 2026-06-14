<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
            'invoice_no'     => ['type' => 'TEXT', 'null' => false],
            'user_id'        => ['type' => 'INTEGER', 'null' => false],
            'total_amount'   => ['type' => 'REAL', 'null' => false, 'default' => 0],
            'payment_amount' => ['type' => 'REAL', 'null' => false, 'default' => 0],
            'change_amount'  => ['type' => 'REAL', 'null' => false, 'default' => 0],
            'note'           => ['type' => 'TEXT', 'null' => true],
            'created_at'     => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('invoice_no');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
