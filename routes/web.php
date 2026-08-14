<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SuperAdminController;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Queue;
use App\Models\Payment;
use App\Models\Clinic;

Route::get('/', function () {
    return response()->json(['message' => 'Hospital SaaS Platform Backend Live']);
});

// REST API Routes v1
Route::prefix('api/v1')->group(function () {
    // 1. Admin Dashboard Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // 2. Doctor Dashboard & Schedules
    Route::get('/doctor/dashboard', function () {
        $doctor = Doctor::with('department')->first();
        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id ?? 1)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'doctor' => $doctor,
                'today_appointments_count' => $appointments->count(),
                'completed_consultations' => 124,
                'total_patients' => 86,
                'appointments' => $appointments
            ]
        ]);
    });

    Route::get('/doctors/schedules', function () {
        return response()->json([
            'status' => 'success',
            'data' => [
                ['doctor_id' => 1, 'doctor_name' => 'Dr. Alex Morgan', 'day' => 'Monday - Friday', 'slots' => ['09:00 AM - 12:00 PM', '02:00 PM - 05:00 PM']],
                ['doctor_id' => 2, 'doctor_name' => 'Dr. Emily Carter', 'day' => 'Monday - Saturday', 'slots' => ['10:00 AM - 01:00 PM', '03:00 PM - 06:00 PM']],
                ['doctor_id' => 3, 'doctor_name' => 'Dr. David Lee', 'day' => 'Tuesday - Sunday', 'slots' => ['08:00 AM - 11:00 AM', '01:00 PM - 04:00 PM']],
            ]
        ]);
    });

    // 3. Patient Dashboard & E-Prescriptions
    Route::get('/patient/dashboard', function () {
        $patient = Patient::first();
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id ?? 1)
            ->get();
        $prescriptions = Prescription::where('patient_id', $patient->id ?? 1)->get();
        $payments = Payment::where('patient_id', $patient->id ?? 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'patient' => $patient,
                'upcoming_appointments_count' => $appointments->where('status', 'Ongoing')->count(),
                'prescriptions_count' => $prescriptions->count() ?: 3,
                'total_billed_npr' => '15,400',
                'currency' => 'NPR',
                'appointments' => $appointments,
                'prescriptions' => $prescriptions,
                'payments' => $payments
            ]
        ]);
    });

    Route::get('/prescriptions', function () {
        $prescriptions = Prescription::get();
        return response()->json(['status' => 'success', 'data' => $prescriptions]);
    });

    Route::post('/prescriptions', function (Request $request) {
        $prescription = Prescription::create([
            'appointment_id' => $request->input('appointment_id', 1),
            'patient_id' => $request->input('patient_id', 1),
            'doctor_id' => $request->input('doctor_id', 1),
            'diagnosis' => $request->input('diagnosis', 'Acute Viral Fever'),
            'medicines_json' => [
                ['name' => 'Paracetamol 500mg', 'dosage' => '1-0-1', 'days' => 5],
                ['name' => 'Amoxicillin 250mg', 'dosage' => '1-1-1', 'days' => 7]
            ],
            'advice' => $request->input('advice', 'Take plenty of rest and stay hydrated.')
        ]);
        return response()->json(['status' => 'success', 'message' => 'Prescription saved', 'data' => $prescription]);
    });

    // 4. Live Queue Token System
    Route::get('/queues', function () {
        return response()->json([
            'status' => 'success',
            'data' => [
                'current_token' => 'TK-104',
                'waiting_count' => 12,
                'avg_wait_minutes' => 15,
                'tokens' => [
                    ['token_number' => 'TK-101', 'patient_name' => 'Sarah Jenkins', 'doctor' => 'Dr. Alex Morgan', 'status' => 'In Consultation'],
                    ['token_number' => 'TK-102', 'patient_name' => 'Michael Brown', 'doctor' => 'Dr. Emily Carter', 'status' => 'In Consultation'],
                    ['token_number' => 'TK-103', 'patient_name' => 'Aayush Thapa', 'doctor' => 'Dr. David Lee', 'status' => 'Next'],
                    ['token_number' => 'TK-104', 'patient_name' => 'Rajesh Sharma', 'doctor' => 'Dr. Alex Morgan', 'status' => 'Waiting'],
                ]
            ]
        ]);
    });

    Route::post('/queues/token', function (Request $request) {
        $tokenNum = 'TK-' . rand(105, 999);
        $queue = Queue::create([
            'token_number' => $tokenNum,
            'patient_id' => $request->input('patient_id', 1),
            'doctor_id' => $request->input('doctor_id', 1),
            'queue_date' => now()->toDateString(),
            'status' => 'Waiting'
        ]);
        return response()->json(['status' => 'success', 'message' => 'Token generated', 'data' => $queue]);
    });

    // 5. Pharmacy & Stock Management
    Route::get('/medicines', function () {
        return response()->json([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'name' => 'Paracetamol 500mg', 'category' => 'Analgesic', 'stock' => 1250, 'price_npr' => '25.00'],
                ['id' => 2, 'name' => 'Amoxicillin 500mg', 'category' => 'Antibiotic', 'stock' => 450, 'price_npr' => '120.00'],
                ['id' => 3, 'name' => 'Metformin 500mg', 'category' => 'Antidiabetic', 'stock' => 890, 'price_npr' => '85.00'],
                ['id' => 4, 'name' => 'Atorvastatin 10mg', 'category' => 'Cardiovascular', 'stock' => 320, 'price_npr' => '150.00'],
            ]
        ]);
    });

    // 6. Invoices & Billing (NPR)
    Route::get('/invoices', function () {
        $payments = Payment::with('patient')->get();
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $payments]);
    });

    // 8. Super Admin & Tenant Clinics
    Route::get('/superadmin/stats', [SuperAdminController::class, 'stats']);
    Route::get('/superadmin/clinics', [SuperAdminController::class, 'clinics']);
    Route::get('/superadmin/plans', [SuperAdminController::class, 'plans']);
    Route::put('/superadmin/plans/{id}', [SuperAdminController::class, 'updatePlan']);

    Route::post('/superadmin/clinics', function (Request $request) {
        $clinic = Clinic::create([
            'name' => $request->input('name', 'New Hospital Branch'),
            'subdomain' => strtolower(str_replace(' ', '', $request->input('name', 'newbranch' . rand(10, 99)))),
            'admin_email' => $request->input('admin_email', 'admin@newbranch.com'),
            'phone' => $request->input('phone', '+977 9800000000'),
            'plan_id' => $request->input('plan_id', 2),
            'status' => 'active',
            'expires_at' => now()->addYear()
        ]);
        return response()->json(['status' => 'success', 'message' => 'Tenant clinic onboarded successfully', 'data' => $clinic]);
    });

    // 9. Core CRUD APIs
    Route::get('/doctors', function () {
        return response()->json(['status' => 'success', 'data' => Doctor::with('department')->get()]);
    });

    Route::post('/doctors', function (Request $request) {
        $doctor = Doctor::create([
            'name' => $request->input('name', 'Dr. New Doctor'),
            'email' => $request->input('email', 'doctor' . rand(100, 999) . '@preclinic.com'),
            'phone' => $request->input('phone', '+1 555-0000'),
            'specialization' => $request->input('specialization', 'General Physician'),
            'consulting_fee' => $request->input('consulting_fee', 150.00),
            'bio' => $request->input('bio', 'Experienced medical specialist.'),
            'status' => 'active'
        ]);
        return response()->json(['status' => 'success', 'message' => 'Doctor added successfully', 'data' => $doctor]);
    });

    Route::get('/doctors/{id}', function ($id) {
        $doctor = Doctor::with(['department', 'appointments.patient'])->find($id) ?: Doctor::with('department')->first();
        return response()->json(['status' => 'success', 'data' => $doctor]);
    });

    Route::get('/patients', function () {
        return response()->json(['status' => 'success', 'data' => Patient::get()]);
    });

    Route::post('/patients', function (Request $request) {
        $patient = Patient::create([
            'patient_code' => 'PT-' . rand(1000, 9999),
            'name' => $request->input('name', 'New Patient'),
            'email' => $request->input('email', 'patient' . rand(100, 999) . '@example.com'),
            'phone' => $request->input('phone', '+1 555-0000'),
            'age' => $request->input('age', 30),
            'gender' => $request->input('gender', 'Male'),
            'blood_group' => $request->input('blood_group', 'O+'),
            'address' => $request->input('address', 'Kathmandu, Nepal')
        ]);
        return response()->json(['status' => 'success', 'message' => 'Patient created successfully', 'data' => $patient]);
    });

    Route::get('/patients/{id}', function ($id) {
        $patient = Patient::with('appointments.doctor')->find($id) ?: Patient::first();
        return response()->json(['status' => 'success', 'data' => $patient]);
    });

    Route::get('/appointments', function () {
        return response()->json(['status' => 'success', 'data' => Appointment::with(['patient', 'doctor'])->get()]);
    });

    Route::post('/appointments', function (Request $request) {
        $appointment = Appointment::create([
            'appointment_number' => 'APT-' . rand(1000, 9999),
            'patient_id' => $request->input('patient_id', 1),
            'doctor_id' => $request->input('doctor_id', 1),
            'appointment_date' => $request->input('appointment_date', now()->toDateString()),
            'time_slot' => $request->input('time_slot', '10:00 AM'),
            'type' => $request->input('type', 'General Visit'),
            'status' => 'Ongoing',
            'reason' => $request->input('reason', 'General Consultation'),
            'fee' => $request->input('fee', 150.00)
        ]);
        return response()->json(['status' => 'success', 'message' => 'Appointment booked successfully', 'data' => $appointment]);
    });
});
