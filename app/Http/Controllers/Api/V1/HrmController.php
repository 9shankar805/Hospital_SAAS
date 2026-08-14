<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\Payroll;

class HrmController extends Controller
{
    // 16.01 GET /api/v1/staff
    public function staff()
    {
        $staff = Staff::with('department')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $staff]);
    }

    // 16.02 POST /api/v1/staff
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'phone'         => 'required|string',
            'email'         => 'required|email',
        ]);

        $s = Staff::create([
            'staff_code'    => 'STF-' . rand(1000, 9999),
            'name'          => $request->name,
            'role'          => $request->role ?? 'Staff',
            'department_id' => $request->department_id,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'salary'        => $request->salary ?? 45000.00,
            'joining_date'  => now()->toDateString(),
            'status'        => 'Active',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Staff added successfully', 'data' => $s], 201);
    }

    // 16.05 GET /api/v1/departments
    public function departments()
    {
        $depts = Department::withCount(['doctors', 'staff'])->get();
        return response()->json(['status' => 'success', 'data' => $depts]);
    }

    // 16.09 GET /api/v1/designations
    public function designations()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'title' => 'Senior Consultant', 'dept' => 'Cardiology', 'count' => 8],
                ['id' => 2, 'title' => 'Head Nurse', 'dept' => 'ICU', 'count' => 12],
                ['id' => 3, 'title' => 'Senior Pharmacist', 'dept' => 'Pharmacy', 'count' => 5],
                ['id' => 4, 'title' => 'Front Desk Executive', 'dept' => 'Reception', 'count' => 10],
            ]
        ]);
    }

    // 16.11 GET /api/v1/attendance
    public function attendance()
    {
        $attendance = Attendance::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $attendance]);
    }

    // 16.14 GET /api/v1/leaves
    public function leaves()
    {
        $leaves = Leave::with('leaveType')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $leaves]);
    }

    // 16.16 PUT /api/v1/leaves/{id}/approve
    public function approveLeave($id)
    {
        $l = Leave::find($id);
        if ($l) $l->update(['status' => 'Approved']);
        return response()->json(['status' => 'success', 'message' => 'Leave approved']);
    }

    // 16.17 PUT /api/v1/leaves/{id}/reject
    public function rejectLeave($id)
    {
        $l = Leave::find($id);
        if ($l) $l->update(['status' => 'Rejected']);
        return response()->json(['status' => 'success', 'message' => 'Leave rejected']);
    }

    // 16.18 GET /api/v1/leave-types
    public function leaveTypes()
    {
        $lt = LeaveType::all();
        return response()->json(['status' => 'success', 'data' => $lt]);
    }

    // 16.20 GET /api/v1/holidays
    public function holidays()
    {
        $h = Holiday::all();
        return response()->json(['status' => 'success', 'data' => $h]);
    }

    // 16.22 GET /api/v1/payroll
    public function payroll()
    {
        $pay = Payroll::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $pay]);
    }
}
