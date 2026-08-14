<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DepartmentsDoctorsSeeder extends Seeder
{
    public function run(): void
    {
        // 3.03 Seed: 6 Departments
        $departmentsData = [
            [
                'name' => 'Cardiology',
                'description' => 'Heart, blood vessels, and cardiovascular health diagnosis & care.',
                'icon' => 'heart',
                'status' => 'active',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Comprehensive healthcare for infants, children, and adolescents.',
                'icon' => 'user-check',
                'status' => 'active',
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Female reproductive health, obstetrics, and maternal care.',
                'icon' => 'female',
                'status' => 'active',
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Bone, joint, spine, and musculoskeletal surgical & non-surgical treatment.',
                'icon' => 'bone',
                'status' => 'active',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Brain, nervous system, spinal cord, and neuromuscular disorder care.',
                'icon' => 'brain',
                'status' => 'active',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, nails health, cosmetic dermatology, and allergy treatment.',
                'icon' => 'bandage',
                'status' => 'active',
            ],
        ];

        $departments = [];
        foreach ($departmentsData as $dep) {
            $departments[$dep['name']] = Department::firstOrCreate(['name' => $dep['name']], $dep);
        }

        // 3.04 Seed: 50 Doctors
        $doctorRole = Role::where('name', 'doctor')->first();

        $firstNames = [
            'Alex', 'Emily', 'David', 'Rajesh', 'Priya', 'Sanjay', 'Ananya', 'Aarav', 'Bibek', 'Sunita',
            'Rohan', 'Kavita', 'Bikash', 'Saraswati', 'Manish', 'Deepa', 'Niraj', 'Shristi', 'Subash', 'Pooja',
            'Suraj', 'Alok', 'Meena', 'Pradeep', 'Bhawana', 'Karan', 'Kriti', 'Dhiren', 'Ritu', 'Sameer',
            'Nisha', 'Ashok', 'Srijana', 'Ganesh', 'Monika', 'Bimal', 'Kala', 'Santosh', 'Sunil', 'Preeti',
            'Anil', 'Nirmala', 'Dinesh', 'Sarmila', 'Mahesh', 'Reena', 'Hari', 'Radha', 'Ramesh', 'Sabina'
        ];

        $lastNames = [
            'Morgan', 'Carter', 'Lee', 'Sharma', 'Adhikari', 'Shrestha', 'Joshi', 'Thapa', 'Karki', 'Bhatt',
            'Giri', 'Khatri', 'Poudel', 'Rana', 'Dahal', 'Bhandari', 'KC', 'Subedi', 'Chhetri', 'Basnet',
            'Niroula', 'Gauchan', 'Ghimire', 'Gurung', 'Tamang', 'Rai', 'Magar', 'Shah', 'Singh', 'Pradhan',
            'Regmi', 'Pandey', 'Devkota', 'Luitel', 'Mainali', 'Neupane', 'Kafle', 'Rijal', 'Oli', 'Mahat',
            'Thakuri', 'Acharya', 'Dhakal', 'Khadka', 'Koirala', 'Sapkota', 'Baniya', 'Timilsina', 'Bhattarai', 'Dutta'
        ];

        $specializations = [
            'Cardiology'  => ['Cardiologist', 'Interventional Cardiologist', 'Electrophysiologist', 'Pediatric Cardiologist'],
            'Pediatrics'  => ['Pediatrician', 'Neonatologist', 'Pediatric Neurologist', 'Child Specialist'],
            'Gynecology'  => ['Gynecologist', 'Obstetrician', 'Reproductive Endocrinologist', 'Fetal Medicine Specialist'],
            'Orthopedics' => ['Orthopedic Surgeon', 'Trauma Specialist', 'Joint Replacement Specialist', 'Spine Surgeon'],
            'Neurology'   => ['Neurologist', 'Neurosurgeon', 'Cognitive Neurologist', 'Stroke Specialist'],
            'Dermatology' => ['Dermatologist', 'Cosmetic Dermatologist', 'Dermatopathologist', 'Trichologist'],
        ];

        $bios = [
            'Senior specialist with 12+ years of clinical and surgical experience.',
            'Lead consultant providing evidence-based patient-centered healthcare.',
            'Expert medical practitioner specializing in advanced diagnostic methods.',
            'Dedicated healthcare consultant committed to compassionate treatment.',
            'Fellowship-trained specialist with international clinical background.'
        ];

        $depKeys = array_keys($departments);

        for ($i = 1; $i <= 50; $i++) {
            $firstName = $firstNames[$i - 1];
            $lastName  = $lastNames[$i - 1];
            $name      = "Dr. {$firstName} {$lastName}";
            $email     = strtolower("{$firstName}.{$lastName}" . ($i > 10 ? $i : "") . "@preclinic.com");
            $email     = preg_replace('/[^a-z0-9@.]/', '', $email);

            $depName = $depKeys[($i - 1) % count($depKeys)];
            $depObj  = $departments[$depName];
            $specList = $specializations[$depName];
            $spec    = $specList[array_rand($specList)];

            $avatarIndex = str_pad((($i - 1) % 10) + 1, 2, '0', STR_PAD_LEFT);
            $avatar = "assets/img/doctors/doctor-{$avatarIndex}.jpg";
            $fee = rand(12, 25) * 100; // NPR 1,200 to NPR 2,500

            // Create Doctor record
            Doctor::firstOrCreate(
                ['email' => $email],
                [
                    'name'           => $name,
                    'phone'          => '+977-98' . rand(10000000, 99999999),
                    'department_id'  => $depObj->id,
                    'specialization' => $spec,
                    'consulting_fee' => $fee,
                    'bio'            => $bios[array_rand($bios)],
                    'avatar'         => $avatar,
                    'status'         => 'active',
                ]
            );

            // Create Doctor User login if role exists
            if ($doctorRole) {
                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $name,
                        'password' => Hash::make('password'),
                        'role_id'  => $doctorRole->id,
                        'phone'    => '+977-98' . rand(10000000, 99999999),
                        'status'   => 'active',
                        'avatar'   => $avatar,
                    ]
                );
            }
        }
    }
}
