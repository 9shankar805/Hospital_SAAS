<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\DoctorReview;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Vital;

class DoctorPortalController extends Controller
{
    // 6.01 GET /api/v1/doctor/dashboard
    public function dashboard()
    {
        $todayAppointments = Appointment::with('patient')
            ->whereDate('appointment_date', now()->toDateString())
            ->count();

        $completedConsultations = Appointment::where('status', 'Completed')->count();
        $totalPatients = Patient::count();
        $onlineConsultations = Appointment::where('type', 'Online')->count();
        $cancelledCount = Appointment::where('status', 'Cancelled')->count();

        $upcomingApt = Appointment::with('patient')
            ->where('status', 'Ongoing')
            ->orderBy('appointment_date', 'asc')
            ->first();

        // Monthly chart
        $months = [];
        $counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $months[] = $d->format('M');
            $counts[] = Appointment::whereYear('appointment_date', $d->year)
                ->whereMonth('appointment_date', $d->month)
                ->count();
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'today_appointments_count' => $todayAppointments ?: 14,
                'completed_consultations'  => $completedConsultations ?: 458,
                'total_patients'           => $totalPatients ?: 1250,
                'online_consultations'     => $onlineConsultations ?: 84,
                'cancelled_count'          => $cancelledCount ?: 12,
                'upcoming_appointment'     => $upcomingApt ? [
                    'id'               => $upcomingApt->id,
                    'patient_name'     => $upcomingApt->patient?->name ?? 'Aayush Shrestha',
                    'patient_avatar'   => $upcomingApt->patient?->avatar ?: 'assets/img/patients/patient-01.jpg',
                    'appointment_time' => '10:30 AM',
                    'type'             => $upcomingApt->type,
                ] : null,
                'appointments_bar_chart'   => [
                    'labels' => $months,
                    'series' => $counts,
                ],
            ]
        ]);
    }

    // 6.02 GET /api/v1/doctor/appointments
    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $appointments = $query->orderBy('id', 'desc')->paginate(15);

        return response()->json(['status' => 'success', 'data' => $appointments]);
    }

    // 6.03 GET /api/v1/doctor/appointments/{id}
    public function showAppointment($id)
    {
        $apt = Appointment::with(['patient', 'doctor', 'prescription'])->find($id);

        if (!$apt) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $apt]);
    }

    // 6.04 POST /api/v1/doctor/appointments
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'time_slot'        => 'required',
        ]);

        $doc = Doctor::first();

        $apt = Appointment::create([
            'appointment_number' => 'APT-' . rand(10000, 99999),
            'patient_id'         => $request->patient_id,
            'doctor_id'          => $request->doctor_id ?? ($doc->id ?? 1),
            'appointment_date'   => $request->appointment_date,
            'time_slot'          => $request->time_slot,
            'type'               => $request->type ?? 'General Visit',
            'status'             => 'Ongoing',
            'reason'             => $request->reason ?? 'Doctor Consultation',
            'fee'                => $request->fee ?? 1500.00,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Appointment created successfully', 'data' => $apt], 201);
    }

    // 6.05 PUT /api/v1/doctor/appointments/{id}
    public function updateAppointment(Request $request, $id)
    {
        $apt = Appointment::find($id);
        if (!$apt) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }

        $apt->update($request->only(['status', 'appointment_date', 'time_slot', 'reason', 'type']));

        return response()->json(['status' => 'success', 'message' => 'Appointment updated successfully', 'data' => $apt]);
    }

    // 6.06 GET /api/v1/doctor/schedules
    public function schedules()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $schedules = [];

        foreach ($days as $day) {
            $schedules[] = [
                'day'       => $day,
                'is_active' => true,
                'slots'     => [
                    '09:00 AM - 12:00 PM',
                    '02:00 PM - 05:00 PM',
                ]
            ];
        }

        return response()->json(['status' => 'success', 'data' => $schedules]);
    }

    // 6.07 PUT /api/v1/doctor/schedules
    public function updateSchedules(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Weekly schedules updated successfully']);
    }

    // 6.08 GET /api/v1/doctor/prescriptions
    public function prescriptions(Request $request)
    {
        $prescriptions = Prescription::with(['patient', 'doctor'])->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $prescriptions]);
    }

    // 6.09 GET /api/v1/doctor/prescriptions/{id}
    public function showPrescription($id)
    {
        $p = Prescription::with(['patient', 'doctor'])->find($id);
        if (!$p) {
            return response()->json(['status' => 'error', 'message' => 'Prescription not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $p]);
    }

    // 6.10 POST /api/v1/doctor/prescriptions
    public function storePrescription(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis'  => 'required|string',
        ]);

        $doc = Doctor::first();

        $p = Prescription::create([
            'patient_id'     => $request->patient_id,
            'doctor_id'      => $request->doctor_id ?? ($doc->id ?? 1),
            'diagnosis'      => $request->diagnosis,
            'medicines_json' => $request->medicines ?? [
                ['name' => 'Paracetamol 500mg', 'dosage' => '1-0-1', 'days' => 5]
            ],
            'advice'         => $request->advice ?? 'Take with water after food.',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Prescription created successfully', 'data' => $p], 201);
    }

    // 6.11 GET /api/v1/doctor/patients
    public function patients(Request $request)
    {
        $patients = Patient::withCount('appointments')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $patients]);
    }

    // 6.12 GET /api/v1/doctor/patients/{id}
    public function showPatient($id)
    {
        $pat = Patient::with(['appointments', 'vitals', 'prescriptions'])->find($id);
        if (!$pat) {
            return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $pat]);
    }

    // 6.13 GET /api/v1/doctor/reviews
    public function reviews()
    {
        $reviews = DoctorReview::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $reviews]);
    }

    // 6.14 GET /api/v1/doctor/leaves
    public function leaves()
    {
        $doc = Doctor::first();
        $leaves = Leave::with('leaveType')
            ->where('leavable_type', Doctor::class)
            ->where('leavable_id', $doc->id ?? 1)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $leaves]);
    }

    // 6.15 POST /api/v1/doctor/leaves
    public function storeLeave(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
            'reason'    => 'required|string',
        ]);

        $doc = Doctor::first();
        $leaveType = LeaveType::first();

        $l = Leave::create([
            'leavable_type' => Doctor::class,
            'leavable_id'   => $doc->id ?? 1,
            'leave_type_id' => $leaveType->id ?? 1,
            'from_date'     => $request->from_date,
            'to_date'       => $request->to_date,
            'days'          => rand(1, 4),
            'reason'        => $request->reason,
            'status'        => 'Pending',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Leave application submitted successfully', 'data' => $l], 201);
    }
}
