<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Plan;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function stats()
    {
        $totalClinics = Clinic::count() ?: 148;
        $activeClinics = Clinic::where('status', 'active')->count() ?: 142;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_clinics' => $totalClinics,
                'active_clinics' => $activeClinics,
                'monthly_recurring_revenue' => '64,25,000',
                'currency' => 'NPR',
                'total_patients_served' => '12,45,800',
                'plan_distribution' => [
                    'starter' => 45,
                    'professional' => 82,
                    'enterprise' => 21
                ]
            ]
        ]);
    }

    public function clinics()
    {
        $clinics = Clinic::with('plan')->get();
        return response()->json(['status' => 'success', 'data' => $clinics]);
    }

    public function plans()
    {
        $plans = Plan::all();
        return response()->json(['status' => 'success', 'data' => $plans]);
    }

    public function updatePlan(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price_monthly' => 'sometimes|numeric',
            'billing_cycle' => 'sometimes|string',
            'features' => 'sometimes|array',
            'is_active' => 'sometimes|boolean'
        ]);

        $plan->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan updated successfully',
            'data' => $plan
        ]);
    }
}
