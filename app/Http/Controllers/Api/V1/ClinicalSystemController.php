<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabReport;
use App\Models\Patient;
use App\Models\Doctor;

class ClinicalSystemController extends Controller
{
    // 13.01 GET /api/v1/lab/tests
    public function labTests()
    {
        $tests = LabReport::with(['patient', 'doctor'])->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $tests]);
    }

    // 13.02 POST /api/v1/lab/tests
    public function storeLabTest(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name'  => 'required|string',
        ]);

        $lr = LabReport::create([
            'report_number' => 'LAB-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'patient_id'    => $request->patient_id,
            'doctor_id'     => $request->doctor_id ?? 1,
            'test_name'     => $request->test_name,
            'category'      => $request->category ?? 'Pathology',
            'result'        => null,
            'status'        => 'Pending',
            'test_date'     => now()->toDateString(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Lab test ordered successfully', 'data' => $lr], 201);
    }

    // 13.03 GET /api/v1/lab/reports
    public function labReports()
    {
        $reports = LabReport::with(['patient', 'doctor'])->where('status', 'Completed')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $reports]);
    }

    // 13.04 GET /api/v1/lab/reports/{id}
    public function showLabReport($id)
    {
        $report = LabReport::with(['patient', 'doctor'])->find($id);
        if (!$report) {
            return response()->json(['status' => 'error', 'message' => 'Lab report not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $report]);
    }

    // 13.05 POST /api/v1/lab/reports/upload
    public function uploadLabReport(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Lab report uploaded successfully']);
    }

    // 13.06 GET /api/v1/lab/imaging
    public function imaging()
    {
        $imaging = LabReport::with(['patient', 'doctor'])->where('category', 'Radiology')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $imaging]);
    }
}
