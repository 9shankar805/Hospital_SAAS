<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'name');

        $users = [
            [
                'name'     => 'Admin User',
                'email'    => 'admin@preclinic.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['admin'],
                'phone'    => '+977-9800000001',
                'status'   => 'active',
            ],
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@preclinic.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['super_admin'],
                'phone'    => '+977-9800000002',
                'status'   => 'active',
            ],
            [
                'name'     => 'Dr. Alex Morgan',
                'email'    => 'alex.morgan@preclinic.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['doctor'],
                'phone'    => '+977-9800000003',
                'status'   => 'active',
            ],
            [
                'name'     => 'Sarah Jenkins',
                'email'    => 'sarah.j@example.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['patient'],
                'phone'    => '+977-9800000004',
                'status'   => 'active',
            ],
            [
                'name'     => 'Nurse Priya',
                'email'    => 'nurse@preclinic.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['nurse'],
                'phone'    => '+977-9800000005',
                'status'   => 'active',
            ],
            [
                'name'     => 'Reception Desk',
                'email'    => 'receptionist@preclinic.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['receptionist'],
                'phone'    => '+977-9800000006',
                'status'   => 'active',
            ],
            [
                'name'     => 'Ram Pharmacist',
                'email'    => 'pharmacist@preclinic.com',
                'password' => Hash::make('password'),
                'role_id'  => $roles['pharmacist'],
                'phone'    => '+977-9800000007',
                'status'   => 'active',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
