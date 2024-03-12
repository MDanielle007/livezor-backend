<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'unique' => true
            ],
            'username' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'password' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'email' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'first_name' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'middle_name' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'last_name' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female', 'Other'],
                'null' => false,
            ],
            'civil_status' => [
                'type' => 'ENUM',
                'constraint' => ['Single', 'Married', 'Windowed', 'Separated', 'Divorced'],
                'null' => false,
            ],
            'sitio' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'barangay' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'city' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'province' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
                'null' => false,
            ],
            'user_image' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'user_role' => [
                'type' => 'ENUM',
                'constraint' => ['DA Personnel', 'Farmer'],
                'null' => false,
            ],
            'user_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'null' => false,
            ],
            'last_login_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'remember_token' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'firebase_token' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('user_accounts');
    }

    public function down()
    {
        $this->forge->dropTable('user_accounts');

    }
}
