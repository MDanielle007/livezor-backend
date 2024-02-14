<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestock_type_id', 'livestock_types', 'id');
        $this->forge->addForeignKey('livestock_breed_id', 'livestock_breeds', 'id');
        $this->forge->addForeignKey('livestock_age_class_id', 'livestock_age_class', 'id');
        $this->forge->processIndexes('livestocks');
    }

    public function down()
    {
        //
    }
}
