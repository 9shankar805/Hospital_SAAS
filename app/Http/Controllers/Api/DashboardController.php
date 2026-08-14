<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Leave;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        $doctorsCount      = Doctor::count();
        $patientsCount     = Patient::count();
        $appointmentsCount = Appointment::count();
        $totalRevenue      = Payment::where('payment_status', 'Paid')->sum('amount');

        $completedCount   = Appointment::where('status', 'Completed')->count();
        $cancelledCount   = Appointment::where('status', 'Cancelled')->count();
        $rescheduledCount = Appointment::where('status', 'Rescheduled')->count();
        $ongoingCount     = Appointment::where('status', 'Ongoing')->count();

        $topDepartments = Department::withCount('doctors')
            ->orderBy('doctors_count', 'desc')
            ->take(5)
            ->get()
            ->pluck('doctors_count', 'name');

        return response()->json([
            'status' => 'success',
            'data' => [
                'doctors_count'         => $doctorsCount,
                'patients_count'        => $patientsCount,
                'appointments_count'    => $appointmentsCount,
                'revenue_total'         => number_format($totalRevenue, 2),
                'currency'              => 'NPR',
                'appointment_breakdown' => [
                    'all'         => $appointmentsCount,
                    'completed'   => $completedCount,
                    'ongoing'     => $ongoingCount,
                    'rescheduled' => $rescheduledCount,
                    'cancelled'   => $cancelledCount,
                ],
                'top_departments' => $topDepartments,
            ]
        ]);
    }

    public function appointmentsChart()
    {
        $months = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $count = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->count();
            $counts[] = $count;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'labels' => $months,
                'series' => $counts,
            ]
        ]);
    }

    public function popularDoctors()
    {
        $doctors = Doctor::with('department')
            ->withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($doc) {
                return [
                    'id'                 => $doc->id,
                    'name'               => $doc->name,
                    'specialization'     => $doc->specialization ?? $doc->department?->name ?? 'General Medicine',
                    'avatar'             => $doc->avatar ?: 'assets/img/doctors/doctor-thumb-01.jpg',
                    'appointment_count' => $doc->appointments_count,
                    'rating'             => 4.9,
                    'fee'                => number_format($doc->consulting_fee ?: 1500, 2),
                ];
            });

        return response()->json(['status' => 'success', 'data' => $doctors]);
    }

    public function doctorScheduleToday()
    {
        $schedules = Doctor::with('department')
            ->take(6)
            ->get()
            ->map(function ($doc) {
                return [
                    'doctor_name'  => $doc->name,
                    'department'   => $doc->department?->name ?? 'OPD',
                    'time_slot'    => '09:00 AM - 02:00 PM',
                    'available'    => true,
                    'avatar'       => $doc->avatar ?: 'assets/img/doctors/doctor-thumb-01.jpg',
                ];
            });

        return response()->json(['status' => 'success', 'data' => $schedules]);
    }

    public function topPatients()
    {
        $patients = Patient::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($pat) {
                return [
                    'id'           => $pat->id,
                    'patient_code' => $pat->patient_code,
                    'name'         => $pat->name,
                    'avatar'       => $pat->avatar ?: 'assets/img/patients/patient-01.jpg',
                    'visits_count' => $pat->appointments_count,
                    'gender'       => $pat->gender,
                    'age'          => $pat->age,
                ];
            });

        return response()->json(['status' => 'success', 'data' => $patients]);
    }

    public function recentTransactions()
    {
        $payments = Payment::with('patient')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(function ($p) {
                return [
                    'id'             => $p->id,
                    'invoice_number' => $p->invoice_number,
                    'patient_name'   => $p->patient?->name ?? 'Walk-in Patient',
                    'amount'         => number_format($p->amount, 2),
                    'payment_method' => $p->payment_method,
                    'status'         => $p->payment_status,
                    'paid_at'        => $p->paid_at ? $p->paid_at->format('M d, Y') : now()->format('M d, Y'),
                ];
            });

        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $payments]);
    }

    public function leaveRequests()
    {
        $leaves = Leave::with(['leaveType', 'leavable'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($l) {
                return [
                    'id'            => $l->id,
                    'employee_name' => $l->leavable?->name ?? 'Staff Member',
                    'leave_type'    => $l->leaveType?->name ?? 'Casual Leave',
                    'from_date'     => $l->from_date ? $l->from_date->format('M d, Y') : '',
                    'to_date'       => $l->to_date ? $l->to_date->format('M d, Y') : '',
                    'days'          => $l->days ?? 1,
                    'status'        => $l->status,
                ];
            });

        return response()->json(['status' => 'success', 'data' => $leaves]);
    }
}
