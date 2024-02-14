<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockAgeClassTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestock_type_id', 'livestock_types', 'id');
        $this->forge->processIndexes('livestock_age_class');
    }

    public function down()
    {
        //
    }
}
