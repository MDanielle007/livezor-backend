<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyEggProcessingBatchTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('user_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('egg_batch_group_id', 'egg_production_batch_group','id');
        $this->forge->processIndexes('egg_processing_batch');
    }

    public function down()
    {
        //
    }
}
