<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'fullname' => 'Administrator',
                'role'     => 'admin',
            ],
            [
                'username' => 'cashier',
                'password' => password_hash('cashier123', PASSWORD_BCRYPT),
                'fullname' => 'Front Desk',
                'role'     => 'cashier',
            ],
            [
                'username' => 'production',
                'password' => password_hash('print123', PASSWORD_BCRYPT),
                'fullname' => 'Operator Catak',
                'role'     => 'production',
            ],
        ];

        // Unique check to avoid duplicates on re-seed
        $db = \Config\Database::connect();
        foreach ($data as $user) {
            $exists = $db->table('users')->where('username', $user['username'])->get()->getRow();
            if (!$exists) {
                $this->db->table('users')->insert($user);
            }
        }
    }
}
