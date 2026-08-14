<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\NotificationLog;
use App\Models\Specialization;
use App\Models\Service;
use App\Models\Asset;

class ClinicOperationsController extends Controller
{
    // -------------------------------------------------------------------------
    // 11.01 GET /api/v1/doctors
    // -------------------------------------------------------------------------
    public function doctors(Request $request)
    {
        $query = Doctor::with('department');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return response()->json(['status' => 'success', 'data' => $query->paginate(15)]);
    }

    // 11.02 GET /api/v1/doctors/{id}
    public function showDoctor($id)
    {
        $doc = Doctor::with(['department', 'appointments', 'reviews'])->find($id);
        if (!$doc) {
            return response()->json(['status' => 'error', 'message' => 'Doctor not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $doc]);
    }

    // 11.03 POST /api/v1/doctors
    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'phone'         => 'required|string',
            'email'         => 'required|email|unique:doctors',
        ]);

        $doc = Doctor::create([
            'name'           => $request->name,
            'department_id'  => $request->department_id,
            'specialization' => $request->specialization ?? 'General Medicine',
            'phone'          => $request->phone,
            'email'          => $request->email,
            'consulting_fee' => $request->consulting_fee ?? 1500.00,
            'bio'            => $request->bio ?? '',
            'status'         => 'active',
            'avatar'         => 'assets/img/doctors/doctor-thumb-01.jpg',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Doctor created successfully', 'data' => $doc], 201);
    }

    // 11.04 PUT /api/v1/doctors/{id}
    public function updateDoctor(Request $request, $id)
    {
        $doc = Doctor::find($id);
        if (!$doc) {
            return response()->json(['status' => 'error', 'message' => 'Doctor not found'], 404);
        }
        $doc->update($request->only(['name', 'phone', 'specialization', 'consulting_fee', 'bio', 'status', 'department_id']));
        return response()->json(['status' => 'success', 'message' => 'Doctor updated successfully', 'data' => $doc]);
    }

    // 11.05 DELETE /api/v1/doctors/{id}
    public function destroyDoctor($id)
    {
        $doc = Doctor::find($id);
        if (!$doc) {
            return response()->json(['status' => 'error', 'message' => 'Doctor not found'], 404);
        }
        $doc->update(['status' => 'inactive']);
        return response()->json(['status' => 'success', 'message' => 'Doctor deactivated successfully']);
    }

    // 11.16 GET /api/v1/doctors/{id}/slots
    public function doctorSlots(Request $request, $id)
    {
        $date = $request->date ?? now()->toDateString();
        return response()->json([
            'status' => 'success',
            'date'   => $date,
            'data'   => [
                '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM',
                '11:00 AM', '11:30 AM', '02:00 PM', '02:30 PM',
                '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM',
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // 11.06 GET /api/v1/patients
    // -------------------------------------------------------------------------
    public function patients(Request $request)
    {
        $query = Patient::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('patient_code', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        return response()->json(['status' => 'success', 'data' => $query->orderBy('id', 'desc')->paginate(15)]);
    }

    // 11.07 GET /api/v1/patients/{id}
    public function showPatient($id)
    {
        $patient = Patient::with(['appointments.doctor', 'vitals', 'prescriptions.doctor', 'labReports', 'payments'])->find($id);
        if (!$patient) {
            return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $patient]);
    }

    // 11.08 POST /api/v1/patients
    public function storePatient(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients',
            'phone' => 'required|string',
        ]);

        $p = Patient::create([
            'patient_code' => 'PT-' . str_pad(Patient::max('id') + 1001, 5, '0', STR_PAD_LEFT),
            'name'         => $request->name,
            'email'        => $request->email ?? null,
            'phone'        => $request->phone,
            'age'          => $request->age ?? null,
            'gender'       => $request->gender ?? 'Male',
            'blood_group'  => $request->blood_group ?? 'O+',
            'address'      => $request->address ?? 'Kathmandu, Nepal',
            'avatar'       => 'assets/img/patients/patient-01.jpg',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Patient created successfully', 'data' => $p], 201);
    }

    // 11.09 PUT /api/v1/patients/{id}
    public function updatePatient(Request $request, $id)
    {
        $p = Patient::find($id);
        if (!$p) {
            return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
        }
        $p->update($request->only(['name', 'email', 'phone', 'age', 'gender', 'blood_group', 'address']));
        return response()->json(['status' => 'success', 'message' => 'Patient updated successfully', 'data' => $p]);
    }

    // 11.10 DELETE /api/v1/patients/{id}
    public function destroyPatient($id)
    {
        $p = Patient::find($id);
        if (!$p) {
            return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
        }
        $p->delete();
        return response()->json(['status' => 'success', 'message' => 'Patient deleted successfully']);
    }

    // -------------------------------------------------------------------------
    // 11.11 GET /api/v1/appointments
    // -------------------------------------------------------------------------
    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        return response()->json(['status' => 'success', 'data' => $query->orderBy('id', 'desc')->paginate(15)]);
    }

    // 11.12 GET /api/v1/appointments/{id}
    public function showAppointment($id)
    {
        $apt = Appointment::with(['patient', 'doctor', 'prescription', 'payment'])->find($id);
        if (!$apt) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $apt]);
    }

    // 11.13 POST /api/v1/appointments
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'time_slot'        => 'required|string',
        ]);

        $apt = Appointment::create([
            'appointment_number' => 'APT-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'patient_id'         => $request->patient_id,
            'doctor_id'          => $request->doctor_id,
            'appointment_date'   => $request->appointment_date,
            'time_slot'          => $request->time_slot,
            'type'               => $request->type ?? 'General Visit',
            'status'             => 'Ongoing',
            'reason'             => $request->reason ?? '',
            'fee'                => $request->fee ?? 1500.00,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Appointment created successfully', 'data' => $apt], 201);
    }

    // 11.14 PUT /api/v1/appointments/{id}
    public function updateAppointment(Request $request, $id)
    {
        $apt = Appointment::find($id);
        if (!$apt) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }
        $apt->update($request->only(['status', 'appointment_date', 'time_slot', 'type', 'reason', 'fee']));
        return response()->json(['status' => 'success', 'message' => 'Appointment updated successfully', 'data' => $apt]);
    }

    // 11.15 DELETE /api/v1/appointments/{id}
    public function destroyAppointment($id)
    {
        $apt = Appointment::find($id);
        if (!$apt) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }
        $apt->update(['status' => 'Cancelled']);
        return response()->json(['status' => 'success', 'message' => 'Appointment cancelled successfully']);
    }

    // -------------------------------------------------------------------------
    // 11.19 GET /api/v1/specializations  (+ POST / PUT / DELETE)
    // -------------------------------------------------------------------------
    public function specializations()
    {
        return response()->json(['status' => 'success', 'data' => Specialization::all()]);
    }

    public function storeSpecialization(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $s = Specialization::create(['name' => $request->name, 'description' => $request->description ?? '', 'status' => 'active']);
        return response()->json(['status' => 'success', 'message' => 'Specialization created', 'data' => $s], 201);
    }

    public function updateSpecialization(Request $request, $id)
    {
        $s = Specialization::find($id);
        if (!$s) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        $s->update($request->only(['name', 'description', 'status']));
        return response()->json(['status' => 'success', 'data' => $s]);
    }

    public function destroySpecialization($id)
    {
        Specialization::find($id)?->delete();
        return response()->json(['status' => 'success', 'message' => 'Specialization deleted']);
    }

    // -------------------------------------------------------------------------
    // 11.18 GET /api/v1/services  (+ POST / PUT / DELETE)
    // -------------------------------------------------------------------------
    public function services()
    {
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => Service::all()]);
    }

    public function storeService(Request $request)
    {
        $request->validate(['name' => 'required|string', 'fee' => 'required|numeric']);
        $s = Service::create(['name' => $request->name, 'description' => $request->description ?? '', 'fee' => $request->fee, 'status' => 'active']);
        return response()->json(['status' => 'success', 'message' => 'Service created', 'data' => $s], 201);
    }

    public function updateService(Request $request, $id)
    {
        $s = Service::find($id);
        if (!$s) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        $s->update($request->only(['name', 'description', 'fee', 'status']));
        return response()->json(['status' => 'success', 'data' => $s]);
    }

    public function destroyService($id)
    {
        Service::find($id)?->delete();
        return response()->json(['status' => 'success', 'message' => 'Service deleted']);
    }

    // -------------------------------------------------------------------------
    // 11.20 GET /api/v1/assets  (+ POST / PUT)
    // -------------------------------------------------------------------------
    public function assets()
    {
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => Asset::orderBy('id', 'desc')->paginate(15)]);
    }

    public function storeAsset(Request $request)
    {
        $request->validate(['name' => 'required|string', 'category' => 'required|string']);
        $a = Asset::create($request->only(['name', 'category', 'serial_number', 'purchase_date', 'purchase_price', 'location', 'status']));
        return response()->json(['status' => 'success', 'message' => 'Asset added', 'data' => $a], 201);
    }

    public function updateAsset(Request $request, $id)
    {
        $a = Asset::find($id);
        if (!$a) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        $a->update($request->only(['name', 'category', 'serial_number', 'purchase_date', 'purchase_price', 'location', 'status']));
        return response()->json(['status' => 'success', 'data' => $a]);
    }

    // -------------------------------------------------------------------------
    // 11.21 GET /api/v1/activities  (audit log)
    // -------------------------------------------------------------------------
    public function activities()
    {
        $activities = NotificationLog::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $activities]);
    }
}
