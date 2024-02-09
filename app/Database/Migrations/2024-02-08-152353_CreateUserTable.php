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
                'unsigned' => true,
                'auto_increment' => true
            ],
            'userID' => [
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
            'firstName' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'middleName' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'lastName' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'dateOfBirth' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female', 'Other'],
                'null' => false,
            ],
            'civilStatus' => [
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
            'phoneNumber' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
                'null' => false,
            ],
            'userImagePath' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'userRole' => [
                'type' => 'ENUM',
                'constraint' => ['DA Personnel', 'Farmer'],
                'null' => false,
            ],
            'userStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'null' => false,
            ],
            'lastLoginDate' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'createdAt' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('userAccounts');
    }

    public function down()
    {
        $this->forge->dropTable('userAccounts');

    }
}
