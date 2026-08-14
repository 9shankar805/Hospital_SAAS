<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Patient;

class UtilityController extends Controller
{
    // 23.01 GET /api/v1/todos
    public function todos()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'task' => 'Review morning ICU rounds notes', 'completed' => true, 'priority' => 'High'],
                ['id' => 2, 'task' => 'Approve pending nurse leave requests', 'completed' => false, 'priority' => 'Medium'],
                ['id' => 3, 'task' => 'Re-order inventory stock for Paracetamol 500mg', 'completed' => false, 'priority' => 'Urgent'],
            ]
        ]);
    }

    // 23.02 GET /api/v1/notes
    public function notes()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'title' => 'Shift Handover Guidelines', 'content' => 'Ensure all vitals logs are uploaded before 8 PM.', 'date' => '2026-07-23'],
                ['id' => 2, 'title' => 'Pediatric OPD Special Slot', 'content' => 'Dr. Sita available on Wednesdays 10 AM - 1 PM.', 'date' => '2026-07-22'],
            ]
        ]);
    }

    // 23.03 GET /api/v1/calendar-events
    public function calendarEvents()
    {
        $appts = Appointment::with(['patient', 'doctor'])->take(20)->get()->map(function($a) {
            return [
                'id'         => 'apt-' . $a->id,
                'title'      => 'Appt: ' . ($a->patient->name ?? 'Patient') . ' w/ ' . ($a->doctor->name ?? 'Doctor'),
                'start'      => $a->appointment_date . 'T' . ($a->appointment_time ?? '10:00:00'),
                'className'  => 'bg-primary-light',
            ];
        });

        return response()->json(['status' => 'success', 'data' => $appts]);
    }

    // 23.04 GET /api/v1/contacts
    public function contacts()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'name' => 'Dr. Rajesh Sharma', 'role' => 'Senior Cardiologist', 'phone' => '+977-9841234567', 'email' => 'rajesh@nepxmedica.com'],
                ['id' => 2, 'name' => 'NepXMedica Emergency Desk', 'role' => 'Helpdesk', 'phone' => '+977-01-4412345', 'email' => 'emergency@nepxmedica.com'],
            ]
        ]);
    }

    // 23.05 GET /api/v1/call-history
    public function callHistory()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'caller' => 'Aayush Shrestha', 'type' => 'Video Call', 'duration' => '12 mins 45 secs', 'status' => 'Completed', 'date' => '2026-07-23 11:30 AM'],
                ['id' => 2, 'caller' => 'Sunita Gurung', 'type' => 'Voice Call', 'duration' => '05 mins 10 secs', 'status' => 'Completed', 'date' => '2026-07-23 09:15 AM'],
            ]
        ]);
    }

    // 23.19 GET /api/v1/reports/appointments
    public function appointmentReport()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'monthly_appointments' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'series' => [320, 450, 410, 520, 580, 640]
                ],
                'by_status' => ['Completed' => 520, 'Pending' => 80, 'Cancelled' => 40],
            ]
        ]);
    }

    // 23.20 GET /api/v1/reports/patients
    public function patientReport()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'gender_distribution' => ['Male' => 48, 'Female' => 50, 'Other' => 2],
                'age_groups'          => ['0-18' => 120, '19-35' => 450, '36-60' => 380, '60+' => 190],
            ]
        ]);
    }
}
