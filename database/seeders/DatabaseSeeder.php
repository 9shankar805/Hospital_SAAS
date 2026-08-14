<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesPermissionsSeeder::class,
            UsersSeeder::class,
            DepartmentsDoctorsSeeder::class,
            SaaSSeeder::class,
            HospitalSeeder::class,
            ComprehensiveSeeder::class,
            ComprehensiveSeederPart2::class,
        ]);
    }
}
