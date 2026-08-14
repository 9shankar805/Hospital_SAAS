<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Specialization;
use App\Models\Service;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Vital;
use App\Models\User;
use App\Models\Role;

class ComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        // 3.05 Seed: 10 Specializations + 10 Services
        $specs = [
            'Cardiology Care', 'Pediatric Care', 'Gynecology & Obstetrics', 'Orthopedic Surgery',
            'Neurology', 'Dermatology', 'Ophthalmology', 'ENT Specialist', 'General Surgery', 'Urology'
        ];
        foreach ($specs as $spec) {
            Specialization::firstOrCreate(['name' => $spec], ['description' => "Specialized {$spec} services."]);
        }

        $services = [
            ['name' => 'General Consultation', 'fee' => 1500.00],
            ['name' => 'ECG Heart Checkup', 'fee' => 2500.00],
            ['name' => 'Blood Pressure Screening', 'fee' => 500.00],
            ['name' => 'Complete Blood Count (CBC)', 'fee' => 1200.00],
            ['name' => 'Chest X-Ray', 'fee' => 1800.00],
            ['name' => 'Ultrasound Scanning', 'fee' => 3500.00],
            ['name' => 'MRI Brain Scan', 'fee' => 12000.00],
            ['name' => 'Dental Cleaning', 'fee' => 2000.00],
            ['name' => 'Eye Refraction Test', 'fee' => 800.00],
            ['name' => 'Physiotherapy Session', 'fee' => 1500.00],
        ];
        foreach ($services as $srv) {
            Service::firstOrCreate(['name' => $srv['name']], ['description' => 'Medical service', 'fee' => $srv['fee']]);
        }

        // 3.06 Seed: 500 Patients
        $firstNames = ['Aayush', 'Sarita', 'Bikram', 'Nirjala', 'Kiran', 'Sita', 'Hari', 'Gita', 'Ram', 'Shyam', 'Deepak', 'Manju', 'Roshan', 'Kamala', 'Subash'];
        $lastNames  = ['Thapa', 'Shrestha', 'Adhikari', 'Joshi', 'Karki', 'Sharma', 'Poudel', 'Dahal', 'Rana', 'Gurung', 'Tamang', 'KC', 'Ghimire', 'Bhatt', 'Rai'];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders     = ['Male', 'Female'];
        $patientRole = Role::where('name', 'patient')->first();

        for ($i = 1; $i <= 500; $i++) {
            $fn = $firstNames[($i - 1) % count($firstNames)];
            $ln = $lastNames[($i - 1) % count($lastNames)];
            $code = 'PT-' . str_pad($i + 1000, 5, '0', STR_PAD_LEFT);
            $email = strtolower("{$fn}.{$ln}.{$i}@example.com");

            $patient = Patient::firstOrCreate(
                ['patient_code' => $code],
                [
                    'name'        => "{$fn} {$ln}",
                    'email'       => $email,
                    'phone'       => '+977-98' . rand(10000000, 99999999),
                    'age'         => rand(18, 75),
                    'gender'      => $genders[$i % 2],
                    'blood_group' => $bloodGroups[$i % count($bloodGroups)],
                    'address'     => 'Kathmandu, Nepal',
                    'avatar'      => 'assets/img/patients/patient-01.jpg',
                ]
            );

            if ($patientRole && $i <= 20) {
                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => "{$fn} {$ln}",
                        'password' => Hash::make('password'),
                        'role_id'  => $patientRole->id,
                        'phone'    => $patient->phone,
                        'status'   => 'active',
                    ]
                );
            }
        }

        // 3.07 Seed: 1000+ Appointments
        $doctors  = Doctor::all();
        $patients = Patient::all();
        $types    = ['General Visit', 'Follow Up', 'Emergency', 'Consultation', 'Checkup'];
        $statuses = ['Completed', 'Completed', 'Ongoing', 'Rescheduled', 'Cancelled'];

        if ($doctors->count() > 0 && $patients->count() > 0) {
            for ($i = 1; $i <= 1050; $i++) {
                $doc = $doctors[($i - 1) % $doctors->count()];
                $pat = $patients[($i - 1) % $patients->count()];
                $aptNum = 'APT-' . str_pad($i + 9000, 5, '0', STR_PAD_LEFT);

                Appointment::firstOrCreate(
                    ['appointment_number' => $aptNum],
                    [
                        'patient_id'       => $pat->id,
                        'doctor_id'        => $doc->id,
                        'appointment_date' => now()->subDays(rand(0, 360))->toDateString(),
                        'time_slot'        => rand(9, 17) . ':00 AM',
                        'type'             => $types[$i % count($types)],
                        'status'           => $statuses[$i % count($statuses)],
                        'reason'           => 'Regular medical consultation',
                        'fee'              => $doc->consulting_fee ?: 1500.00,
                    ]
                );
            }
        }

        // 3.08 Seed: 500 Payments
        $appointments = Appointment::take(500)->get();
        $methods = ['Cash', 'Credit Card', 'Online Pay', 'Insurance'];
        $pStatuses = ['Paid', 'Paid', 'Paid', 'Pending'];

        foreach ($appointments as $idx => $apt) {
            Payment::firstOrCreate(
                ['invoice_number' => 'INV-2026-' . str_pad($idx + 1, 5, '0', STR_PAD_LEFT)],
                [
                    'patient_id'     => $apt->patient_id,
                    'appointment_id' => $apt->id,
                    'amount'         => $apt->fee ?: 1500.00,
                    'payment_method' => $methods[$idx % count($methods)],
                    'payment_status' => $pStatuses[$idx % count($pStatuses)],
                    'paid_at'        => now()->subDays(rand(0, 180)),
                ]
            );
        }

        // 3.09 Seed: 200 Prescriptions
        $completedApts = Appointment::where('status', 'Completed')->take(200)->get();
        $diagnoses = ['Acute Bronchitis', 'Hypertension', 'Type 2 Diabetes', 'Gastritis', 'Seasonal Allergy'];

        foreach ($completedApts as $apt) {
            Prescription::firstOrCreate(
                ['appointment_id' => $apt->id],
                [
                    'patient_id'     => $apt->patient_id,
                    'doctor_id'      => $apt->doctor_id,
                    'diagnosis'      => $diagnoses[rand(0, count($diagnoses) - 1)],
                    'medicines_json' => [
                        ['name' => 'Paracetamol 500mg', 'dosage' => '1-0-1', 'days' => 5],
                        ['name' => 'Amoxicillin 250mg', 'dosage' => '1-1-1', 'days' => 7]
                    ],
                    'advice'         => 'Take medicine after food and rest well.',
                ]
            );
        }

        // 3.10 Seed: 2000 Vitals
        $patList = Patient::take(100)->get();
        $vStatuses = ['Normal', 'Normal', 'Monitor', 'Critical'];

        foreach ($patList as $pat) {
            for ($k = 0; $k < 20; $k++) {
                Vital::create([
                    'patient_id'     => $pat->id,
                    'logged_at'      => now()->subDays($k),
                    'blood_pressure' => rand(110, 140) . '/' . rand(70, 90),
                    'heart_rate'     => rand(65, 95),
                    'oxygen_level'   => rand(95, 99),
                    'temperature'    => rand(97, 100) . '.4',
                    'weight'         => rand(50, 85),
                    'height'         => rand(155, 180),
                    'status'         => $vStatuses[$k % count($vStatuses)],
                ]);
            }
        }
    }
}
