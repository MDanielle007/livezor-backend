<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCalculateAgeTrigger extends Migration
{
    public function up()
    {
        $trigger = "
            CREATE TRIGGER calculate_age_trigger
            BEFORE INSERT ON `livestocks`
            FOR EACH ROW
            BEGIN
                SET NEW.age_days = DATEDIFF(CURRENT_DATE(), NEW.date_of_birth);
                SET NEW.age_weeks = FLOOR(NEW.age_days / 7);
                SET NEW.age_months = FLOOR(NEW.age_days / 30);
                SET NEW.age_years = FLOOR(NEW.age_days / 365);
            END
        ";
        $this->db->query($trigger);
    }

    public function down()
    {
        $this->db->query('DROP TRIGGER IF EXISTS calculate_age_trigger');
    }
}
