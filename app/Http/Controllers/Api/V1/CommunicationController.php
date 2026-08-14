<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationLog;

class CommunicationController extends Controller
{
    // 22.01 GET /api/v1/messages
    public function messages()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'sender' => 'Dr. Rajesh Sharma', 'last_message' => 'Please check the latest CBC report for Patient Aayush.', 'time' => '10:45 AM', 'unread' => 2],
                ['id' => 2, 'sender' => 'Nurse Anita Rai', 'last_message' => 'Vitals recorded for ICU Bed 4.', 'time' => '09:30 AM', 'unread' => 0],
            ]
        ]);
    }

    // 22.02 GET /api/v1/messages/{id}
    public function showConversation($id)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 10, 'sender' => 'Dr. Rajesh Sharma', 'message' => 'Hello, please review CBC report for Patient Aayush.', 'time' => '10:40 AM', 'is_me' => false],
                ['id' => 11, 'sender' => 'You', 'message' => 'Reviewed and sent to laboratory.', 'time' => '10:45 AM', 'is_me' => true],
            ]
        ]);
    }

    // 22.03 POST /api/v1/messages
    public function sendMessage(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Message sent successfully']);
    }

    // 22.04 GET /api/v1/notifications
    public function notifications()
    {
        $notifications = NotificationLog::orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(fn($n) => [
                'id'       => $n->id,
                'title'    => $n->title,
                'message'  => $n->message,
                'type'     => $n->type,
                'is_read'  => (bool) $n->is_read,
                'time'     => $n->created_at?->diffForHumans() ?? 'just now',
            ]);

        $unread = NotificationLog::where('is_read', false)->count();

        return response()->json([
            'status'       => 'success',
            'unread_count' => $unread,
            'data'         => $notifications,
        ]);
    }

    // 22.05 POST /api/v1/notifications/{id}/read
    public function markNotificationRead($id)
    {
        $n = NotificationLog::find($id);
        if ($n) {
            $n->update(['is_read' => true]);
        }
        return response()->json(['status' => 'success', 'message' => 'Notification marked as read']);
    }

    // 22.06 POST /api/v1/notifications/read-all
    public function markAllNotificationsRead()
    {
        NotificationLog::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['status' => 'success', 'message' => 'All notifications marked as read']);
    }

    // 22.12 GET /api/v1/announcements
    public function announcements()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'title' => 'Annual Medical Audit Scheduled for Aug 5', 'target_role' => 'All Staff', 'date' => '2026-07-23', 'status' => 'Active'],
                ['id' => 2, 'title' => 'New ICU Protocol Mandatory Training', 'target_role' => 'Nurses & Doctors', 'date' => '2026-07-20', 'status' => 'Active'],
            ]
        ]);
    }
}
