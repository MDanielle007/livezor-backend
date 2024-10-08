<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockAgeClassTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'livestock_age_classification' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'age_class_range' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
            ],
            'age_min_days' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'age_max_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'is_offspring' => [
                'type' => 'BOOL',
                'default' => false,
            ],
            'sex' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'breeding_eligibility' => [
                'type' => 'BOOL',
                'default' => false,
            ],
            'livestock_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->createTable('livestock_age_class');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_age_class');
    }
}
