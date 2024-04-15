<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyEggMonitoringLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('user_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('egg_process_batch_id', 'egg_processing_batch','id');
        $this->forge->processIndexes('egg_monitoring_logs');
    }

    public function down()
    {
        //
    }
}
