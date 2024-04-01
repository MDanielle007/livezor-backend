<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DewormingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'dewormer_id' => '2',
                'livestock_id' => '1',
                'deworming_reason' => 'Reason 1',
                'deworming_remarks' => 'Deworming Remarks',
                'deworming_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'dewormer_id' => '2',
                'livestock_id' => '2',
                'deworming_reason' => 'Reason 2',
                'deworming_remarks' => 'Deworming Remarks',
                'deworming_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'dewormer_id' => '2',
                'livestock_id' => '3',
                'deworming_reason' => 'Reason 3',
                'deworming_remarks' => 'Deworming Remarks',
                'deworming_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '4',
                'dewormer_id' => '2',
                'livestock_id' => '4',
                'deworming_reason' => 'Reason 4',
                'deworming_remarks' => 'Deworming Remarks',
                'deworming_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestock_dewormings')->insertBatch($data);
    }
}
