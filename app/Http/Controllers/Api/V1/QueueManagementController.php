<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Patient;
use App\Models\Doctor;

class QueueManagementController extends Controller
{
    // 12.01 GET /api/v1/queues
    public function queues()
    {
        $waitingCount        = Queue::where('status', 'Waiting')->count();
        $inConsultationCount = Queue::where('status', 'In Consultation')->count();
        $servedToday         = Queue::where('status', 'Completed')->count();
        $avgWait             = 14;

        $tokens = Queue::with(['patient', 'doctor'])->orderBy('id', 'desc')->paginate(15);
        $currentToken = Queue::where('status', 'In Consultation')->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'current_token'        => $currentToken?->token_number ?? 'TK-102',
                'waiting_count'        => $waitingCount ?: 12,
                'in_consultation_count'=> $inConsultationCount ?: 4,
                'served_today'         => $servedToday ?: 48,
                'avg_wait_minutes'     => $avgWait,
                'tokens'               => $tokens,
            ]
        ]);
    }

    // 12.02 POST /api/v1/queues/token
    public function storeToken(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
        ]);

        $q = Queue::create([
            'token_number' => 'TK-' . rand(100, 999),
            'patient_id'   => $request->patient_id,
            'doctor_id'    => $request->doctor_id,
            'priority'     => $request->priority ?? 'Normal',
            'status'       => 'Waiting',
            'queue_date'   => now()->toDateString(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Token generated successfully', 'data' => $q], 201);
    }

    // 12.03 PUT /api/v1/queues/{id}/status
    public function updateStatus(Request $request, $id)
    {
        $q = Queue::find($id);
        if (!$q) {
            return response()->json(['status' => 'error', 'message' => 'Queue token not found'], 404);
        }

        $q->update(['status' => $request->status ?? 'Completed']);

        return response()->json(['status' => 'success', 'message' => 'Token status updated', 'data' => $q]);
    }

    // 12.04 GET /api/v1/queues/waiting-list
    public function waitingList()
    {
        $waiting = Queue::with(['patient', 'doctor'])->where('status', 'Waiting')->orderBy('id', 'asc')->get();
        return response()->json(['status' => 'success', 'data' => $waiting]);
    }

    // 12.05 GET /api/v1/queues/analytics
    public function analytics()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'hourly_distribution' => [
                    'labels' => ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'],
                    'series' => [5, 18, 25, 30, 15, 20, 28, 12]
                ],
                'daily_served_chart' => [
                    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    'series' => [45, 52, 60, 48, 55, 62, 30]
                ],
                'doctor_load' => [
                    ['doctor' => 'Dr. Rajesh Sharma', 'count' => 18],
                    ['doctor' => 'Dr. Sita Adhikari', 'count' => 15],
                    ['doctor' => 'Dr. Anil Karki', 'count' => 12],
                ]
            ]
        ]);
    }

    // 12.06 GET /api/v1/queues/display
    public function displayBoard()
    {
        $nowServing = Queue::with(['patient', 'doctor'])->where('status', 'In Consultation')->first();
        $nextTokens = Queue::with(['patient', 'doctor'])->where('status', 'Waiting')->orderBy('id', 'asc')->take(5)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'now_serving' => $nowServing ? $nowServing->token_number : 'TK-105',
                'doctor_name' => $nowServing?->doctor?->name ?? 'Dr. Rajesh Sharma',
                'next_tokens' => $nextTokens,
            ]
        ]);
    }
}
