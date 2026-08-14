<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiAutomationController extends Controller
{
    // 14.01 POST /api/v1/ai/chat
    public function chat(Request $request)
    {
        $msg = strtolower($request->message ?? '');
        $reply = "Based on clinical protocol, NepXMedica AI Medical Assistant recommends reviewing patient vitals and past medical history.";

        if (str_contains($msg, 'fever') || str_contains($msg, 'headache')) {
            $reply = "Patient presenting with fever and headache: Recommend checking temperature, blood pressure, CBC, and scheduling OPD consultation.";
        } else if (str_contains($msg, 'dose') || str_contains($msg, 'medicine')) {
            $reply = "Adult Dosage Guidelines: Paracetamol 500mg - 1000mg q6h max 4g/day. Amoxicillin 500mg t.i.d for 7 days.";
        }

        return response()->json([
            'status' => 'success',
            'reply'  => $reply,
            'suggestions' => ['Check Patient Vitals', 'Order CBC Test', 'Schedule Consultation']
        ]);
    }

    // 14.02 POST /api/v1/ai/smart-diagnosis
    public function smartDiagnosis(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'differentials' => [
                    ['disease' => 'Acute Viral Pyrexia', 'confidence' => 92, 'description' => 'High probability based on fever and elevated pulse rate.'],
                    ['disease' => 'Upper Respiratory Tract Infection', 'confidence' => 78, 'description' => 'Moderate confidence given throat complaint.'],
                    ['disease' => 'Typhoid Fever', 'confidence' => 45, 'description' => 'Low probability. Recommend Widal test if fever > 5 days.'],
                ],
                'recommended_tests' => ['Complete Blood Count (CBC)', 'ESR / CRP', 'Widal Slide Test'],
                'urgency_level' => 'Moderate',
            ]
        ]);
    }

    // 14.03 GET /api/v1/ai/diagnosis-sessions
    public function diagnosisSessions()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'patient_name' => 'Aayush Shrestha', 'complaint' => 'Fever & Fatigue', 'result' => 'Acute Viral Pyrexia (92%)', 'status' => 'Confirmed'],
                ['id' => 2, 'patient_name' => 'Sunita Gurung', 'complaint' => 'Chest Tightness', 'result' => 'Angina Pectoris (85%)', 'status' => 'Confirmed'],
                ['id' => 3, 'patient_name' => 'Bikram Thapa', 'complaint' => 'Joint Pain', 'result' => 'Rheumatoid Arthritis (74%)', 'status' => 'Pending'],
            ]
        ]);
    }

    // 14.04 GET /api/v1/ai/model-accuracy-chart
    public function modelAccuracyChart()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'labels'            => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'accuracy_series'   => [91.2, 92.5, 93.8, 94.2, 95.1, 96.4],
                'precision_series'  => [89.5, 90.8, 92.1, 93.0, 94.2, 95.8],
            ]
        ]);
    }

    // 14.05 POST /api/v1/ai/risk-prediction
    public function riskPrediction(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'risk_score'   => 78,
                'risk_level'   => 'High Risk',
                'risk_factors' => ['Hypertension (BP 145/95)', 'High BMI (29.4)', 'Family History of Cardiovascular Disease'],
            ]
        ]);
    }

    // 14.06 POST /api/v1/ai/auto-schedule
    public function autoSchedule(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['day' => 'Monday', 'slots' => ['09:00 AM - 12:00 PM', '02:00 PM - 05:00 PM']],
                ['day' => 'Wednesday', 'slots' => ['10:00 AM - 01:00 PM']],
                ['day' => 'Friday', 'slots' => ['09:00 AM - 12:00 PM', '03:00 PM - 06:00 PM']],
            ]
        ]);
    }

    // 14.07 POST /api/v1/ai/voice-notes
    public function voiceNotes(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'transcript'      => "Patient complains of persistent headache and low grade fever for 3 days. Prescribed Paracetamol 500mg t.i.d. Advised rest.",
                'structured_note' => "Chief Complaint: Headache & Fever\nDuration: 3 days\nPlan: Paracetamol 500mg t.i.d",
            ]
        ]);
    }
}
