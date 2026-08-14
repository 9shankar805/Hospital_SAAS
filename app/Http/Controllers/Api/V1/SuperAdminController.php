<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinic;

class SuperAdminController extends Controller
{
    // 21.01 GET /api/v1/superadmin/stats
    public function stats()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_clinics'             => 48,
                'active_clinics'            => 42,
                'monthly_recurring_revenue' => 1250000,
                'total_patients_served'     => 18500,
                'plan_distribution'         => ['starter' => 18, 'pro' => 22, 'enterprise' => 8],
                'mrr_growth_chart'          => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'series' => [850000, 920000, 1050000, 1120000, 1180000, 1250000]
                ],
                'new_tenants_chart'         => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'series' => [4, 6, 5, 7, 8, 9]
                ],
                'currency'                  => 'NPR'
            ]
        ]);
    }

    // 21.02 GET /api/v1/superadmin/clinics
    public function clinics()
    {
        $clinics = Clinic::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $clinics]);
    }

    // 21.03 POST /api/v1/superadmin/clinics
    public function storeClinic(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'subdomain'  => 'required|string|unique:clinics,subdomain',
            'admin_email'=> 'required|email',
        ]);

        $c = Clinic::create([
            'name'         => $request->name,
            'subdomain'    => $request->subdomain,
            'admin_email'  => $request->admin_email,
            'plan'         => $request->plan ?? 'Pro',
            'status'       => 'Active',
            'renewal_date' => now()->addYear()->toDateString(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Tenant clinic provisioned successfully', 'data' => $c], 201);
    }

    // 21.05 PUT /api/v1/superadmin/clinics/{id}/suspend
    public function suspendClinic($id)
    {
        $c = Clinic::find($id);
        if ($c) $c->update(['status' => 'Suspended']);
        return response()->json(['status' => 'success', 'message' => 'Clinic suspended successfully']);
    }

    // 21.06 PUT /api/v1/superadmin/clinics/{id}/activate
    public function activateClinic($id)
    {
        $c = Clinic::find($id);
        if ($c) $c->update(['status' => 'Active']);
        return response()->json(['status' => 'success', 'message' => 'Clinic activated successfully']);
    }

    // 21.07 GET /api/v1/superadmin/plans
    public function plans()
    {
        return response()->json([
            'status' => 'success',
            'currency' => 'NPR',
            'data'   => [
                ['id' => 1, 'name' => 'Starter Plan', 'price' => 15000, 'billing' => 'monthly', 'features' => ['Up to 5 Doctors', '500 Patients/mo', 'Basic Queue System', 'Email Support']],
                ['id' => 2, 'name' => 'Pro Plan', 'price' => 35000, 'billing' => 'monthly', 'features' => ['Up to 25 Doctors', 'Unlimited Patients', 'AI Assistant & Smart Diagnosis', 'Priority Support']],
                ['id' => 3, 'name' => 'Enterprise Plan', 'price' => 75000, 'billing' => 'monthly', 'features' => ['Unlimited Doctors', 'Multi-Branch Management', 'Custom Domain & SLA', '24/7 Dedicated Support']],
            ]
        ]);
    }
}
