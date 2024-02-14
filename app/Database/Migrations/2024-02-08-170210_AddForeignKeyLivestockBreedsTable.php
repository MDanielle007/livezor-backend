<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockBreedsTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestock_type_id', 'livestock_types', 'id');
        $this->forge->processIndexes('livestock_breeds');
    }

    public function down()
    {
        //
    }
}
