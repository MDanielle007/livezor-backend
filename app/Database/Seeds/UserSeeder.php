<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // php spark db:seed UserSeeder
        // php spark migrate    

        $data = [
            [
                'id' => '1',
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
            // puerto galera
            [
                'id' => '2',
                'user_id' => 'FMR-JDC202303201139',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerJuan002',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Juan',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Dela Cruz',
                'date_of_birth' => '2000-01-01', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Aninuan',
                'city' => 'City Name',
                'province' => 'Puerto Galera',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '3',
                'user_id' => 'FMR-JS202309030812',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerJian003',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Jian',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Sevilla',
                'date_of_birth' => '1998-07-06', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Sabang',
                'city' => 'City Name',
                'province' => 'Puerto Galera',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // san teodoro
            [
                'id' => '4',
                'user_id' => 'FMR-JA202311280843',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerJames021',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'James',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Arevalo',
                'date_of_birth' => '1978-01-21', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Poblacion',
                'city' => 'City Name',
                'province' => 'San Teodoro',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '5',
                'user_id' => 'FMR-RSA202310030855',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerRose025',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Rose',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Sta. Ana',
                'date_of_birth' => '1999-11-29', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Tacligan',
                'city' => 'City Name',
                'province' => 'San Teodoro',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //baco
            [
                'id' => '6',
                'user_id' => 'FMR-DW202304030901',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerDenna003',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Deanna',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Wong',
                'date_of_birth' => '1999-07-18', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Dulangan I',
                'city' => 'City Name',
                'province' => 'Baco',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '7',
                'user_id' => 'FMR-AC202204050243',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerAntonio015',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Antonio',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Cruz',
                'date_of_birth' => '1983-06-28', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Water',
                'city' => 'City Name',
                'province' => 'Baco',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //bansud
            [
                'id' => '8',
                'user_id' => 'FMR-MG202304061001',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerManuel008',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Manuel',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Garcia',
                'date_of_birth' => '1994-08-09', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Rosacara',
                'city' => 'City Name',
                'province' => 'Bansud',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '9',
                'user_id' => 'FMR-JR202304070230',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerJose009',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Jose',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Reyes',
                'date_of_birth' => '1996-04-17', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Alcadesma',
                'city' => 'City Name',
                'province' => 'Bansud',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //bongabong
            [
                'id' => '10',
                'user_id' => 'FMR-JS202304071021',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerJuan010',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Juan',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Santos',
                'date_of_birth' => '2000-01-01', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Labasan',
                'city' => 'City Name',
                'province' => 'Bongabong',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '11',
                'user_id' => 'FMR-RR202204080735',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerRodrigo011',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Rodrigo',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Rivera',
                'date_of_birth' => '1979-10-22', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Lisap',
                'city' => 'City Name',
                'province' => 'Bongabong',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //bulalacao
            [
                'id' => '12',
                'user_id' => 'FMR-EF202304090740',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerEduardo012',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Eduardo',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Fernandez',
                'date_of_birth' => '1985-12-05', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Balatasan',
                'city' => 'City Name',
                'province' => 'Bulalacao',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '13',
                'user_id' => 'FMR-FDC202304100510',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerFelipe012',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Felipe',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Dela Cruz',
                'date_of_birth' => '1993-01-18', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Maasin',
                'city' => 'City Name',
                'province' => 'Bulalacao',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //calapan
            [
                'id' => '14',
                'user_id' => 'FMR-FR202304110146',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerFrancisco014',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Francisco',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Reyes',
                'date_of_birth' => '1993-03-31', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Malad',
                'city' => 'City Name',
                'province' => 'Calapan',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '15',
                'user_id' => 'FMR-GS202304121111',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerGabriel015',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Gabriel',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Santos',
                'date_of_birth' => '1981-05-14', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Putingtubig',
                'city' => 'City Name',
                'province' => 'Calapan',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //gloria
            [
                'id' => '16',
                'user_id' => 'FMR-GG202304130208',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerGuillermo016',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Guillermo',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Gonzales',
                'date_of_birth' => '2000-07-26', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Agos',
                'city' => 'City Name',
                'province' => 'Gloria',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '17',
                'user_id' => 'FMR-IR202304140411',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerIsidro017',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Isidro',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Ramirez',
                'date_of_birth' => '1986-09-91', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Malamig',
                'city' => 'City Name',
                'province' => 'Gloria',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //mansalay
            [
                'id' => '18',
                'user_id' => 'FMR-LG202201150731',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerLeon018',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Leon ',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Guerrer0',
                'date_of_birth' => '1997-11-23', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Cabalwa',
                'city' => 'City Name',
                'province' => 'Mansalay',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '19',
                'user_id' => 'FMR-LG202201160345',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerLorenzo019',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Lorenzo',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Garcia',
                'date_of_birth' => '1984-02-06', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Wasig',
                'city' => 'City Name',
                'province' => 'Mansalay',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //naujan
            [
                'id' => '20',
                'user_id' => 'FMR-MS202202170834',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerMariano020',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Mariano',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Santos',
                'date_of_birth' => '1982-04-29', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Barcenaga',
                'city' => 'City Name',
                'province' => 'Naujan',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '21',
                'user_id' => 'FMR-NR202303181129',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerNarciso021',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Narciso',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Reyes',
                'date_of_birth' => '1989-06-10', // Fill with actual date of birth
                'gender' => 'Male',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Bagong Buhay',
                'city' => 'City Name',
                'province' => 'Naujan',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //pinamalayan
            [
                'id' => '22',
                'user_id' => 'FMR-GS202202191225',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerGuadalupe022',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Guadalupe',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Santos',
                'date_of_birth' => '1989-05-20', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Anoling',
                'city' => 'City Name',
                'province' => 'Pinamalayan',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '23',
                'user_id' => 'FMR-FDR202102201128',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerFelicidad023',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Felicidad',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Delos Reyes',
                'date_of_birth' => '1981-03-12', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Nabuslot',
                'city' => 'City Name',
                'province' => 'Pinamalayan',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //pola
            [
                'id' => '24',
                'user_id' => 'FMR-ER202101210931',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerEstrella024',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Estrella',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Ramirez',
                'date_of_birth' => '1986-01-31', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Bacungan',
                'city' => 'City Name',
                'province' => 'Pola',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '25',
                'user_id' => 'FMR-CF202303220130',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerConsuelo025',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Consuelo',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Fernandez',
                'date_of_birth' => '1998-12-07', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Calima',
                'city' => 'City Name',
                'province' => 'Pola',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //roxas
            [
                'id' => '26',
                'user_id' => 'FMR-AR202304230522',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerAurora026',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Aurora',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Reyes',
                'date_of_birth' => '1987-12-07', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Libertad',
                'city' => 'City Name',
                'province' => 'Roxas',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '27',
                'user_id' => 'FMR-JG202204240136',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerJosefina027',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Josefina',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Gonzales',
                'date_of_birth' => '199-08-14', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'San Aquilino',
                'city' => 'City Name',
                'province' => 'Roxas',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //socorro
            [
                'id' => '28',
                'user_id' => 'FMR-CS202204250311',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerCorazon028',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Corazon',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Santos',
                'date_of_birth' => '1992-06-30', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Batong Dalig',
                'city' => 'City Name',
                'province' => 'Socorro',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '29',
                'user_id' => 'FMR-ER202304261018',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerEmelita0029',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Emelita',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Reyes',
                'date_of_birth' => '1984-04-19', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Ma. Concepcion',
                'city' => 'City Name',
                'province' => 'Socorro',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            //victoria
            [
                'id' => '30',
                'user_id' => 'FMR-LC202301261024',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerLuningning030',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Luningning',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Cruz',
                'date_of_birth' => '1982-05-05', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Married',
                'sitio' => 'Sitio Name',
                'barangay' => 'Alcate',
                'city' => 'City Name',
                'province' => 'Victoria',
                'phone_number' => '12345678901',
                'user_image' => 'farmer.jpg', // Replace with actual image file name
                'user_role' => 'Farmer',
                'user_status' => 'Active',
                'last_login_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'id' => '31',
                'user_id' => 'FMR-CL202301041021',// FMR-[initials ng pangalan][date kung nagregister year|month|day|hour|min]
                'username' => 'farmerCarmela031',
                'password' => '$2y$10$MggA1ebEg2Ai4gNDj7eTzet2x6nO7sB9bdHThJK50tfiukXoMm2t2', // Replace with actual hashed password
                'email' => 'farmer@example.com',
                'first_name' => 'Carmela',
                'middle_name' => '', // Fill with appropriate middle name if available
                'last_name' => 'Lucila',
                'date_of_birth' => '2000-10-23', // Fill with actual date of birth
                'gender' => 'Female',
                'civil_status' => 'Single',
                'sitio' => 'Sitio Name',
                'barangay' => 'Bambanin',
                'city' => 'City Name',
                'province' => 'Victoria',
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
