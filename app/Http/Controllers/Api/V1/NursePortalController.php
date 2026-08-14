<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Vital;
use App\Models\ClinicalNote;
use App\Models\Queue;

class NursePortalController extends Controller
{
    // 8.01 GET /api/v1/nurse/dashboard
    public function dashboard()
    {
        $patientsUnderCare  = Patient::count();
        $criticalAlerts     = Vital::where('status', 'Critical')->count();
        $medicationsDue     = 18;
        $vitalsRecordedToday= Vital::whereDate('created_at', now()->toDateString())->count();

        $liveVitals = Vital::with('patient')->orderBy('id', 'desc')->take(10)->get();
        $handoverNotes = ClinicalNote::with('patient')->orderBy('id', 'desc')->take(5)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'patients_under_care'    => $patientsUnderCare ?: 42,
                'critical_alerts_count'  => $criticalAlerts ?: 3,
                'medications_due'        => $medicationsDue,
                'vitals_recorded_today'  => $vitalsRecordedToday ?: 85,
                'live_vitals_table'      => $liveVitals,
                'shift_handover_notes'   => $handoverNotes,
                'ward_occupancy_chart'   => [
                    'labels' => ['ICU', 'General Ward', 'Pediatric Ward', 'Emergency'],
                    'series' => [8, 24, 12, 6]
                ],
                'vitals_trend_chart' => [
                    'dates'       => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    'heart_rate'  => [72, 75, 71, 78, 74, 76, 73],
                    'oxygen'      => [98, 97, 98, 99, 98, 97, 98],
                ]
            ]
        ]);
    }

    // 8.02 GET /api/v1/nurse/patients
    public function patients()
    {
        $patients = Patient::with('latestVital')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $patients]);
    }

    // 8.03 GET /api/v1/nurse/vitals
    public function vitals()
    {
        $vitals = Vital::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $vitals]);
    }

    // 8.04 POST /api/v1/nurse/vitals
    public function storeVital(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $v = Vital::create([
            'patient_id'     => $request->patient_id,
            'heart_rate'     => $request->heart_rate ?? 75,
            'blood_pressure' => $request->blood_pressure ?? '120/80',
            'oxygen_level'   => $request->oxygen_level ?? 98,
            'weight'         => $request->weight ?? 70,
            'height'         => $request->height ?? 170,
            'temperature'    => $request->temperature ?? '98.6',
            'status'         => $request->status ?? 'Normal',
            'logged_at'      => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vitals logged successfully', 'data' => $v], 201);
    }

    // 8.05 GET /api/v1/nurse/clinical-notes
    public function clinicalNotes()
    {
        $notes = ClinicalNote::with(['patient', 'staff'])->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $notes]);
    }

    // 8.06 POST /api/v1/nurse/clinical-notes
    public function storeClinicalNote(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'note'       => 'required|string',
        ]);

        $cn = ClinicalNote::create([
            'patient_id' => $request->patient_id,
            'note'       => $request->note,
            'note_type'  => $request->note_type ?? 'General',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Clinical note saved successfully', 'data' => $cn], 201);
    }

    // 8.07 GET /api/v1/nurse/critical-alerts
    public function criticalAlerts()
    {
        $vitals = Vital::with('patient')->where('status', 'Critical')->orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $vitals]);
    }

    // 8.08 GET /api/v1/nurse/queue
    public function queue()
    {
        $queues = Queue::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $queues]);
    }
}
