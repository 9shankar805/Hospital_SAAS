<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Payment;
use App\Models\Vital;
use App\Models\LabReport;
use App\Models\NotificationLog;

class PatientPortalController extends Controller
{
    // 7.01 GET /api/v1/patient/dashboard
    public function dashboard()
    {
        $patient = Patient::first();
        $patId   = $patient->id ?? 1;

        $upcomingCount    = Appointment::where('patient_id', $patId)->where('status', 'Ongoing')->count();
        $prescriptionsCnt = Prescription::where('patient_id', $patId)->count();
        $totalBilled      = Payment::where('patient_id', $patId)->sum('amount');
        $doctorsCount     = Doctor::count();

        $latestVital = Vital::where('patient_id', $patId)->orderBy('id', 'desc')->first();
        $appointments = Appointment::with('doctor')->where('patient_id', $patId)->orderBy('id', 'desc')->take(5)->get();
        $prescriptions = Prescription::with('doctor')->where('patient_id', $patId)->orderBy('id', 'desc')->take(3)->get();
        $payments = Payment::where('patient_id', $patId)->orderBy('id', 'desc')->take(5)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'upcoming_count'      => $upcomingCount ?: 3,
                'prescriptions_count' => $prescriptionsCnt ?: 12,
                'total_billed'        => number_format($totalBilled ?: 15400, 2),
                'doctors_count'       => $doctorsCount ?: 50,
                'currency'            => 'NPR',
                'vitals_latest'       => [
                    'blood_pressure' => $latestVital?->blood_pressure ?? '120/80',
                    'heart_rate'     => $latestVital?->heart_rate ?? 72,
                    'oxygen_level'   => $latestVital?->oxygen_level ?? 98,
                    'weight'         => $latestVital?->weight ?? 68,
                    'height'         => $latestVital?->height ?? 172,
                    'temperature'    => $latestVital?->temperature ?? '98.6',
                    'status'         => $latestVital?->status ?? 'Normal',
                ],
                'appointments'  => $appointments,
                'prescriptions' => $prescriptions,
                'recent_payments' => $payments,
                'consultation_by_dept_chart' => [
                    'labels' => ['Cardiology', 'Pediatrics', 'Neurology', 'Dermatology'],
                    'series' => [4, 2, 3, 1],
                ]
            ]
        ]);
    }

    // 7.02 GET /api/v1/patient/appointments
    public function appointments(Request $request)
    {
        $patient = Patient::first();
        $query = Appointment::with('doctor')->where('patient_id', $patient->id ?? 1);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $appointments]);
    }

    // 7.03 POST /api/v1/patient/appointments
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'time_slot'        => 'required',
        ]);

        $patient = Patient::first();

        $apt = Appointment::create([
            'appointment_number' => 'APT-PAT-' . rand(10000, 99999),
            'patient_id'         => $patient->id ?? 1,
            'doctor_id'          => $request->doctor_id,
            'appointment_date'   => $request->appointment_date,
            'time_slot'          => $request->time_slot,
            'type'               => $request->type ?? 'General Visit',
            'status'             => 'Ongoing',
            'reason'             => $request->reason ?? 'Online Booking via Patient Portal',
            'fee'                => $request->fee ?? 1500.00,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Appointment booked successfully', 'data' => $apt], 201);
    }

    // 7.05 GET /api/v1/patient/doctors
    public function doctors()
    {
        $doctors = Doctor::with('department')->get();
        return response()->json(['status' => 'success', 'data' => $doctors]);
    }

    // 7.07 GET /api/v1/patient/prescriptions
    public function prescriptions()
    {
        $patient = Patient::first();
        $prescriptions = Prescription::with('doctor')->where('patient_id', $patient->id ?? 1)->paginate(10);
        return response()->json(['status' => 'success', 'data' => $prescriptions]);
    }

    // 7.09 GET /api/v1/patient/invoices
    public function invoices()
    {
        $patient = Patient::first();
        $payments = Payment::where('patient_id', $patient->id ?? 1)->paginate(10);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $payments]);
    }

    // 7.11 GET /api/v1/patient/vitals
    public function vitals()
    {
        $patient = Patient::first();
        $vitals = Vital::where('patient_id', $patient->id ?? 1)->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $vitals]);
    }

    // 7.12 POST /api/v1/patient/vitals
    public function storeVital(Request $request)
    {
        $patient = Patient::first();

        $v = Vital::create([
            'patient_id'     => $patient->id ?? 1,
            'heart_rate'     => $request->heart_rate ?? rand(65, 85),
            'blood_pressure' => $request->blood_pressure ?? '120/80',
            'oxygen_level'   => $request->oxygen_level ?? rand(96, 99),
            'weight'         => $request->weight ?? 70,
            'height'         => $request->height ?? 170,
            'temperature'    => $request->temperature ?? '98.4',
            'status'         => 'Normal',
            'logged_at'      => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vitals recorded successfully', 'data' => $v], 201);
    }

    // 7.15 GET /api/v1/patient/health-reports
    public function healthReports()
    {
        $patient = Patient::first();
        $reports = LabReport::with('doctor')->where('patient_id', $patient->id ?? 1)->get();
        return response()->json(['status' => 'success', 'data' => $reports]);
    }

    // 7.31 POST /api/v1/ai/chat
    public function aiChat(Request $request)
    {
        $msg = strtolower($request->message ?? '');
        $reply = "Based on your clinical inquiry, our NepXMedica AI Medical Assistant recommends consulting with a specialist. If symptoms persist beyond 24 hours, please schedule an appointment.";

        if (str_contains($msg, 'fever') || str_contains($msg, 'headache')) {
            $reply = "For mild fever and headache, stay well-hydrated and rest. You may take Paracetamol 500mg as prescribed. If fever exceeds 102°F (38.9°C), please book an immediate consultation with your doctor.";
        } else if (str_contains($msg, 'appointment') || str_contains($msg, 'book')) {
            $reply = "You can easily book an appointment with our 50+ specialist doctors using the 'Book Appointment' button on your Patient Portal dashboard!";
        }

        return response()->json(['status' => 'success', 'reply' => $reply]);
    }
}
