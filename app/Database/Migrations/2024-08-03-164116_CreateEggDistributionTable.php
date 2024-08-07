<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEggDistributionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'number_of_eggs' => [
                'type' => 'INT',
                'null' => false,
            ],
            'poultry_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'poultry_breed_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'recipient_user_id' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => true,
            ],
            'recipient_first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'recipient_middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'recipient_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'recipient_barangay' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'recipient_city_municipality' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'recipient_province' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'recipient_contact_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ],
            'date_of_distribution' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
            'farmer_association' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('egg_distribution');
    }

    public function down()
    {
        $this->forge->dropTable('egg_distribution');
    }
}
