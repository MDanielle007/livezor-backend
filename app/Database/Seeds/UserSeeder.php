<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => 'DAP-MDC202403211239',
                'username' => 'danielle@gmail.com',
                'password' => '$2y$10$t0x6dpy0loj1GlPzJJBbeO4yhT0iEv/2MEDff2Jc5KVeKeRXsojVW', // Replace with actual hashed password
                'email' => 'admin@example.com',
                'first_name' => 'Marc Danielle',
                'middle_name' => 'Marasigan', // Fill with appropriate middle name if available
                'last_name' => 'Cabatay',
                'date_of_birth' => '2002-10-26', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Barangay Name',
                'city' => 'City Name',
                'province' => 'Province Name',
                'phone_number' => '12345678901',
                'user_image' => 'admin.jpg', // Replace with actual image file name
                'user_role' => 'DA Personnel',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 'FMR-JDC202403201139',
                'username' => 'farmerDanielle007',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Juan',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Dela Cruz',
                'date_of_birth' => '2000-01-01', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Barangay Name',
                'city' => 'City Name',
                'province' => 'Province Name',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('user_accounts')->insertBatch($data);
    }
}
