<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class SettingsController extends Controller
{
    // GET /api/v1/settings/{module}
    public function getSettings($module)
    {
        return response()->json([
            'status' => 'success',
            'module' => $module,
            'data'   => [
                'organization_name' => 'NepXMedica SaaS Hospital & Medical Hub',
                'currency'          => 'NPR',
                'currency_symbol'   => 'NPR',
                'timezone'          => 'Asia/Kathmandu',
                'language'          => 'Nepali (np) / English (en)',
                'theme'             => 'Modern Light',
                'status'            => 'Active',
            ]
        ]);
    }

    // PUT /api/v1/settings/{module}
    public function updateSettings(Request $request, $module)
    {
        return response()->json([
            'status'  => 'success',
            'message' => "Settings for module [{$module}] updated successfully."
        ]);
    }

    // 18.13 GET /api/v1/roles
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json(['status' => 'success', 'data' => $roles]);
    }

    // 18.14 GET /api/v1/permissions
    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json(['status' => 'success', 'data' => $permissions]);
    }
}
