<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'       => ['type' => 'TEXT', 'null' => false],
            'email'      => ['type' => 'TEXT', 'null' => false],
            'password'   => ['type' => 'TEXT', 'null' => false],
            'role'       => ['type' => 'TEXT', 'null' => false, 'default' => 'kasir'],
            'is_active'  => ['type' => 'INTEGER', 'null' => false, 'default' => 1],
            'created_at' => ['type' => 'TEXT', 'null' => true],
            'updated_at' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
