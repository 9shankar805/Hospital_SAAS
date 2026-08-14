<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\Payment;

class ReceptionistPortalController extends Controller
{
    // 9.01 GET /api/v1/receptionist/dashboard
    public function dashboard()
    {
        $todayAppointments = Appointment::whereDate('appointment_date', now()->toDateString())->count();
        $checkedInCount    = Queue::where('status', 'In Consultation')->count();
        $queueCount        = Queue::where('status', 'Waiting')->count();
        $pendingRequests   = Appointment::where('status', 'Ongoing')->count();

        $todayTable = Appointment::with(['patient', 'doctor'])->orderBy('id', 'desc')->take(10)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'today_appointments' => $todayAppointments ?: 45,
                'checked_in_count'   => $checkedInCount ?: 18,
                'queue_count'        => $queueCount ?: 12,
                'pending_requests'   => $pendingRequests ?: 6,
                'today_appointments_table' => $todayTable,
                'daily_footfall_chart' => [
                    'labels' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
                    'series' => [12, 45, 38, 29, 34, 15]
                ],
                'appointments_by_channel_chart' => [
                    'labels' => ['Walk-in', 'Online Website', 'Phone Call', 'Mobile App'],
                    'series' => [40, 35, 15, 10]
                ]
            ]
        ]);
    }

    // 9.02 GET /api/v1/receptionist/appointments
    public function appointments()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $appointments]);
    }

    // 9.03 POST /api/v1/receptionist/appointments
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'time_slot'        => 'required',
        ]);

        $apt = Appointment::create([
            'appointment_number' => 'APT-REC-' . rand(10000, 99999),
            'patient_id'         => $request->patient_id,
            'doctor_id'          => $request->doctor_id,
            'appointment_date'   => $request->appointment_date,
            'time_slot'          => $request->time_slot,
            'type'               => $request->type ?? 'General Visit',
            'status'             => 'Ongoing',
            'reason'             => $request->reason ?? 'Reception Desk Booking',
            'fee'                => $request->fee ?? 1500.00,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Appointment booked by Receptionist', 'data' => $apt], 201);
    }

    // 9.04 GET /api/v1/receptionist/patients
    public function patients()
    {
        $patients = Patient::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $patients]);
    }

    // 9.05 POST /api/v1/receptionist/patients
    public function storePatient(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:patients',
            'phone' => 'required|string',
        ]);

        $pat = Patient::create([
            'patient_code' => 'PT-' . rand(10000, 99999),
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'age'          => $request->age ?? 30,
            'gender'       => $request->gender ?? 'Male',
            'blood_group'  => $request->blood_group ?? 'O+',
            'address'      => $request->address ?? 'Kathmandu, Nepal',
            'avatar'       => 'assets/img/patients/patient-01.jpg',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Patient registered successfully', 'data' => $pat], 201);
    }

    // 9.06 GET /api/v1/receptionist/queue
    public function queue()
    {
        $queues = Queue::with(['patient', 'doctor'])->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $queues]);
    }

    // 9.07 POST /api/v1/receptionist/queue/token
    public function generateToken(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
        ]);

        $q = Queue::create([
            'token_number' => 'TK-' . rand(100, 999),
            'patient_id'   => $request->patient_id,
            'doctor_id'    => $request->doctor_id,
            'queue_date'   => now()->toDateString(),
            'status'       => 'Waiting',
        ]);

        return response()->json(['status' => 'success', 'message' => 'OPD Queue token generated', 'data' => $q], 201);
    }

    // 9.08 GET /api/v1/receptionist/payments
    public function payments()
    {
        $payments = Payment::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $payments]);
    }
}
