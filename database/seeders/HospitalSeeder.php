<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments
        $cardio = Department::firstOrCreate(['name' => 'Cardiology'], ['description' => 'Heart and vascular system medical care.', 'icon' => 'heart']);
        $pedia = Department::firstOrCreate(['name' => 'Pediatrics'], ['description' => 'Medical care for infants, children, and adolescents.', 'icon' => 'user-check']);
        $gyna = Department::firstOrCreate(['name' => 'Gynecology'], ['description' => 'Female reproductive system healthcare.', 'icon' => 'female']);

        // 2. Doctors
        $doc1 = Doctor::firstOrCreate(
            ['email' => 'alex.morgan@preclinic.com'],
            [
                'name' => 'Dr. Alex Morgan',
                'phone' => '+1 555-0192',
                'department_id' => $cardio->id,
                'specialization' => 'Cardiologist',
                'consulting_fee' => 1500.00,
                'bio' => 'Senior Cardiologist with 12+ years of experience.',
                'status' => 'active'
            ]
        );

        $doc2 = Doctor::firstOrCreate(
            ['email' => 'emily.carter@preclinic.com'],
            [
                'name' => 'Dr. Emily Carter',
                'phone' => '+1 555-0193',
                'department_id' => $pedia->id,
                'specialization' => 'Pediatrician',
                'consulting_fee' => 1200.00,
                'bio' => 'Lead Pediatrician specializing in child healthcare.',
                'status' => 'active'
            ]
        );

        // 3. Patients
        $pat1 = Patient::firstOrCreate(
            ['patient_code' => 'PT-1001'],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.j@example.com',
                'phone' => '+1 555-0811',
                'age' => 32,
                'gender' => 'Female',
                'blood_group' => 'A+'
            ]
        );

        $pat2 = Patient::firstOrCreate(
            ['patient_code' => 'PT-1002'],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.b@example.com',
                'phone' => '+1 555-0812',
                'age' => 45,
                'gender' => 'Male',
                'blood_group' => 'O+'
            ]
        );

        // 4. Appointments
        $app1 = Appointment::firstOrCreate(
            ['appointment_number' => 'APT-9011'],
            [
                'patient_id' => $pat1->id,
                'doctor_id' => $doc1->id,
                'appointment_date' => now()->toDateString(),
                'time_slot' => '10:00 AM',
                'type' => 'General Visit',
                'status' => 'Ongoing',
                'fee' => 1500.00
            ]
        );

        $app2 = Appointment::firstOrCreate(
            ['appointment_number' => 'APT-9012'],
            [
                'patient_id' => $pat2->id,
                'doctor_id' => $doc2->id,
                'appointment_date' => now()->toDateString(),
                'time_slot' => '11:30 AM',
                'type' => 'Follow Up',
                'status' => 'Completed',
                'fee' => 1200.00
            ]
        );

        // 5. Payments
        Payment::firstOrCreate(
            ['invoice_number' => 'INV-2026-001'],
            [
                'patient_id' => $pat1->id,
                'appointment_id' => $app1->id,
                'amount' => 1500.00,
                'payment_method' => 'Credit Card',
                'payment_status' => 'Paid',
                'paid_at' => now()
            ]
        );
    }
}
