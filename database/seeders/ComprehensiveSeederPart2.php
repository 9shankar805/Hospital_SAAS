<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Payroll;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\StockTransfer;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\Income;
use App\Models\LabReport;
use App\Models\ClinicalNote;
use App\Models\NotificationLog;
use App\Models\DoctorReview;
use App\Models\InsuranceClaim;
use App\Models\Asset;
use App\Models\Queue;
use App\Models\Plan;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;

class ComprehensiveSeederPart2 extends Seeder
{
    public function run(): void
    {
        $cardio = Department::first();

        // 3.11 Seed: 20 Staff
        $rolesStaff = ['Nurse', 'Receptionist', 'Pharmacist', 'Lab Technician'];
        for ($i = 1; $i <= 20; $i++) {
            $roleName = $rolesStaff[($i - 1) % count($rolesStaff)];
            Staff::firstOrCreate(
                ['email' => "staff{$i}@preclinic.com"],
                [
                    'staff_code'    => 'STF-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'name'          => "Staff Member {$i}",
                    'role'          => $roleName,
                    'department_id' => $cardio->id ?? 1,
                    'phone'         => '+977-98' . rand(10000000, 99999999),
                    'salary'        => rand(30, 60) * 1000,
                    'joining_date'  => now()->subMonths(rand(1, 36))->toDateString(),
                    'status'        => 'active',
                ]
            );
        }

        // 3.12 Seed: Leave Types + 60 Leaves
        $lTypes = ['Casual Leave', 'Sick Leave', 'Annual Leave', 'Maternity Leave'];
        $leaveObjs = [];
        foreach ($lTypes as $lt) {
            $leaveObjs[] = LeaveType::firstOrCreate(['name' => $lt], ['days_allowed' => 15]);
        }

        $allStaff = Staff::all();
        foreach ($allStaff as $idx => $st) {
            for ($l = 0; $l < 3; $l++) {
                Leave::create([
                    'leavable_type' => Staff::class,
                    'leavable_id'   => $st->id,
                    'leave_type_id' => $leaveObjs[$l % count($leaveObjs)]->id,
                    'from_date'     => now()->subDays(rand(10, 20))->toDateString(),
                    'to_date'       => now()->subDays(rand(1, 9))->toDateString(),
                    'days'          => rand(1, 5),
                    'reason'        => 'Personal leave request',
                    'status'        => ['Approved', 'Pending', 'Rejected'][$l % 3],
                ]);
            }
        }

        // 3.13 Seed: 300 Attendance records
        foreach ($allStaff as $st) {
            for ($d = 0; $d < 15; $d++) {
                Attendance::create([
                    'attendable_type' => Staff::class,
                    'attendable_id'   => $st->id,
                    'date'            => now()->subDays($d)->toDateString(),
                    'check_in'        => '09:00:00',
                    'check_out'       => '17:00:00',
                    'status'          => 'Present',
                ]);
            }
        }

        // 3.14 Seed: 10 Holidays
        $holidays = [
            ['name' => 'Dashain Festival', 'date' => '2026-10-15'],
            ['name' => 'Tihar Festival', 'date' => '2026-11-05'],
            ['name' => 'New Year', 'date' => '2027-01-01'],
            ['name' => 'Constitution Day', 'date' => '2026-09-19'],
            ['name' => 'Martyrs Day', 'date' => '2027-01-30'],
            ['name' => 'Democracy Day', 'date' => '2027-02-19'],
            ['name' => 'Maha Shivaratri', 'date' => '2027-03-08'],
            ['name' => 'Holi Festival', 'date' => '2027-03-24'],
            ['name' => 'Nepali New Year', 'date' => '2027-04-14'],
            ['name' => 'Buddha Jayanti', 'date' => '2027-05-23'],
        ];
        foreach ($holidays as $h) {
            Holiday::firstOrCreate(['name' => $h['name']], ['date' => $h['date']]);
        }

        // 3.15 Seed: Payroll records
        foreach ($allStaff as $st) {
            Payroll::create([
                'payrollable_type' => Staff::class,
                'payrollable_id'   => $st->id,
                'month'            => '2026-07',
                'basic_salary'     => $st->salary ?: 40000.00,
                'allowances'       => 5000.00,
                'deductions'       => 2000.00,
                'net_salary'       => ($st->salary ?: 40000.00) + 3000.00,
                'payment_status'   => 'Paid',
            ]);
        }

        // 3.16 Seed: 100 Medicines
        $medObjs = [];
        for ($m = 1; $m <= 100; $m++) {
            $medObjs[] = Medicine::firstOrCreate(
                ['name' => "Medicine Item {$m}"],
                [
                    'generic_name' => "Generic Paracetamol {$m}",
                    'category'     => ['Analgesic', 'Antibiotic', 'Antidiabetic', 'Cardiovascular', 'Vitamins'][$m % 5],
                    'stock'        => rand(50, 2000),
                    'price'        => rand(20, 500) . '.00',
                    'unit'         => 'Tablet',
                    'batch_no'     => 'BATCH-' . rand(100, 999),
                    'expiry_date'  => now()->addMonths(rand(3, 24))->toDateString(),
                ]
            );
        }

        // 3.17 Seed: 15 Suppliers
        $suppliers = [];
        for ($s = 1; $s <= 15; $s++) {
            $suppliers[] = Supplier::firstOrCreate(
                ['email' => "supplier{$s}@pharma.com"],
                [
                    'name'    => "Nepal Pharma Supplier {$s}",
                    'phone'   => '+977-1-4' . rand(100000, 999999),
                    'address' => 'Kathmandu, Nepal',
                    'status'  => 'active',
                ]
            );
        }

        // 3.18 Seed: 50 Purchase Orders
        for ($po = 1; $po <= 50; $po++) {
            PurchaseOrder::firstOrCreate(
                ['po_number' => 'PO-2026-' . str_pad($po, 4, '0', STR_PAD_LEFT)],
                [
                    'supplier_id'  => $suppliers[($po - 1) % count($suppliers)]->id,
                    'medicine_id'  => $medObjs[($po - 1) % count($medObjs)]->id,
                    'quantity'     => rand(10, 50),
                    'unit_price'   => 100.00,
                    'total_amount' => rand(10, 50) * 100.00,
                    'status'       => ['Completed', 'Pending', 'Delivered'][$po % 3],
                    'order_date'   => now()->subDays(rand(1, 90))->toDateString(),
                ]
            );
        }

        // 3.19 Seed: 30 Stock Transfers
        for ($st = 1; $st <= 30; $st++) {
            StockTransfer::create([
                'medicine_id'   => $medObjs[($st - 1) % count($medObjs)]->id,
                'from_location' => 'Central Store',
                'to_location'   => 'OPD Pharmacy',
                'quantity'      => rand(10, 100),
                'status'        => 'Completed',
            ]);
        }

        // 3.20 Seed: 6 Expense Categories + 200 Expense records
        $expCats = ['Utility Bills', 'Medical Supplies', 'Staff Salary', 'Maintenance', 'Marketing', 'Software & SaaS'];
        $expObjs = [];
        foreach ($expCats as $ec) {
            $expObjs[] = ExpenseCategory::firstOrCreate(['name' => $ec]);
        }

        for ($ex = 1; $ex <= 200; $ex++) {
            Expense::create([
                'category_id'  => $expObjs[$ex % count($expObjs)]->id,
                'title'        => "Hospital Operating Expense {$ex}",
                'amount'       => rand(5, 50) * 1000,
                'expense_date' => now()->subDays(rand(1, 180))->toDateString(),
            ]);
        }

        // 3.21 Seed: 200 Income records
        for ($inc = 1; $inc <= 200; $inc++) {
            Income::create([
                'source'      => ['OPD Services', 'IPD Billing', 'Pharmacy Sales', 'Lab Tests'][$inc % 4],
                'amount'      => rand(10, 100) * 1000,
                'income_date' => now()->subDays(rand(1, 180))->toDateString(),
                'notes'       => "Hospital Service Income {$inc}",
            ]);
        }

        // 3.22 Seed: 150 Lab Reports
        $patList = Patient::take(50)->get();
        $docList = Doctor::take(10)->get();
        $labTests = ['Blood Glucose Test', 'Lipid Profile', 'Thyroid Panel', 'Liver Function Test', 'Kidney Function Test'];

        for ($lr = 1; $lr <= 150; $lr++) {
            LabReport::firstOrCreate(
                ['report_number' => 'LAB-2026-' . str_pad($lr, 5, '0', STR_PAD_LEFT)],
                [
                    'patient_id'  => $patList[($lr - 1) % $patList->count()]->id,
                    'doctor_id'   => $docList[($lr - 1) % $docList->count()]->id,
                    'test_name'   => $labTests[$lr % count($labTests)],
                    'result'      => 'All parameters within normal clinical reference ranges.',
                    'status'      => ['Completed', 'Pending'][$lr % 2],
                    'test_date'   => now()->subDays(rand(1, 60))->toDateString(),
                ]
            );
        }

        // 3.23 Seed: 40 Clinical Notes
        for ($cn = 1; $cn <= 40; $cn++) {
            ClinicalNote::create([
                'patient_id' => $patList[($cn - 1) % $patList->count()]->id,
                'staff_id'   => $allStaff[($cn - 1) % $allStaff->count()]->id,
                'note'       => 'Patient shows steady recovery. Recommended continuing current medication regimen.',
                'note_type'  => 'General',
            ]);
        }

        // 3.24 Seed: 100 Notifications
        for ($n = 1; $n <= 100; $n++) {
            NotificationLog::create([
                'notifiable_type' => Patient::class,
                'notifiable_id'   => $patList[($n - 1) % $patList->count()]->id,
                'title'           => 'Appointment & Prescription Reminder',
                'message'         => 'Your appointment schedule has been confirmed by Preclinic System.',
                'is_read'         => false,
            ]);
        }

        // 3.25 Seed: Doctor Reviews
        foreach ($docList as $doc) {
            for ($r = 1; $r <= 5; $r++) {
                DoctorReview::create([
                    'doctor_id'  => $doc->id,
                    'patient_id' => $patList[$r % $patList->count()]->id,
                    'rating'     => rand(4, 5),
                    'review'     => 'Excellent doctor! Very attentive and caring staff.',
                    'status'     => 'approved',
                ]);
            }
        }

        // 3.26 Seed: 30 Insurance Claims
        for ($ic = 1; $ic <= 30; $ic++) {
            InsuranceClaim::firstOrCreate(
                ['claim_number' => 'CLM-2026-' . str_pad($ic, 4, '0', STR_PAD_LEFT)],
                [
                    'patient_id'    => $patList[($ic - 1) % $patList->count()]->id,
                    'insurer_name'  => 'MetLife Nepal Insurance',
                    'amount'        => rand(15, 80) * 1000,
                    'status'        => ['Submitted', 'Approved', 'Under Review'][$ic % 3],
                ]
            );
        }

        // 3.27 Seed: 20 Assets
        $assets = ['CT Scanner', 'X-Ray Machine', 'Ultrasound System', 'Patient Ventilator', 'ECG Monitor', 'Infusion Pump'];
        for ($ast = 1; $ast <= 20; $ast++) {
            Asset::create([
                'name'          => $assets[$ast % count($assets)] . " #{$ast}",
                'category'      => 'Medical Equipment',
                'purchase_price'=> rand(50, 500) * 1000,
                'status'        => 'Active',
            ]);
        }

        // 3.28 Seed: 100 Queue tokens
        for ($q = 1; $q <= 100; $q++) {
            Queue::create([
                'token_number' => 'TK-' . str_pad($q + 100, 3, '0', STR_PAD_LEFT),
                'patient_id'   => $patList[($q - 1) % $patList->count()]->id,
                'doctor_id'    => $docList[($q - 1) % $docList->count()]->id,
                'queue_date'   => now()->subDays(rand(0, 7))->toDateString(),
                'status'       => ['Waiting', 'In Consultation', 'Completed', 'Skipped'][$q % 4],
            ]);
        }

        // 3.29 Seed: SaaS Data — 3 Plans + 10 Clinics
        for ($cl = 1; $cl <= 10; $cl++) {
            Clinic::firstOrCreate(
                ['subdomain' => "clinicbranch{$cl}"],
                [
                    'name'        => "Nepal Health Center Branch {$cl}",
                    'admin_email' => "admin@branch{$cl}.nepxmedica.com",
                    'phone'       => "+977-1-4" . rand(100000, 999999),
                    'plan_id'     => rand(1, 3),
                    'status'      => 'active',
                    'expires_at'  => now()->addMonths(rand(6, 24)),
                ]
            );
        }
    }
}
