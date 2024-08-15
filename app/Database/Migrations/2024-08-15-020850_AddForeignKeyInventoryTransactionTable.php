<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyInventoryTransactionTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('batch_id', 'inventory_item_batches','id');
        $this->forge->processIndexes('inventory_transactions');
    }

    public function down()
    {
        //
    }
}
