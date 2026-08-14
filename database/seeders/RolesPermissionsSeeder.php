<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $roles = [
            ['name' => 'admin',        'display_name' => 'Administrator',  'description' => 'Full system access'],
            ['name' => 'doctor',       'display_name' => 'Doctor',          'description' => 'Doctor portal access'],
            ['name' => 'patient',      'display_name' => 'Patient',         'description' => 'Patient portal access'],
            ['name' => 'nurse',        'display_name' => 'Nurse',           'description' => 'Nurse portal access'],
            ['name' => 'receptionist', 'display_name' => 'Receptionist',    'description' => 'Front desk access'],
            ['name' => 'pharmacist',   'display_name' => 'Pharmacist',      'description' => 'Pharmacy access'],
            ['name' => 'super_admin',  'display_name' => 'Super Admin',     'description' => 'SaaS platform owner'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['name' => $roleData['name']], $roleData);
        }

        // Modules × Actions
        $modules = [
            'dashboard', 'doctors', 'patients', 'appointments',
            'prescriptions', 'queue', 'pharmacy', 'lab',
            'hrm', 'finance', 'reports', 'settings',
            'content', 'support', 'clinics',
        ];
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}"],
                    ['module' => $module, 'action' => $action]
                );
            }
        }

        // Assign all permissions to admin
        $admin      = Role::where('name', 'admin')->first();
        $superAdmin = Role::where('name', 'super_admin')->first();
        $allPerms   = Permission::all();

        $admin->permissions()->sync($allPerms->pluck('id'));
        $superAdmin->permissions()->sync($allPerms->pluck('id'));

        // Doctor permissions
        $doctor = Role::where('name', 'doctor')->first();
        $doctorPerms = Permission::whereIn('module', ['dashboard', 'appointments', 'prescriptions', 'patients', 'queue'])
            ->whereIn('action', ['view', 'create', 'edit'])
            ->pluck('id');
        $doctor->permissions()->sync($doctorPerms);

        // Nurse permissions
        $nurse = Role::where('name', 'nurse')->first();
        $nursePerms = Permission::whereIn('module', ['dashboard', 'patients', 'queue', 'lab'])
            ->whereIn('action', ['view', 'create', 'edit'])
            ->pluck('id');
        $nurse->permissions()->sync($nursePerms);

        // Receptionist permissions
        $receptionist = Role::where('name', 'receptionist')->first();
        $receptionistPerms = Permission::whereIn('module', ['dashboard', 'appointments', 'patients', 'queue', 'finance'])
            ->whereIn('action', ['view', 'create', 'edit'])
            ->pluck('id');
        $receptionist->permissions()->sync($receptionistPerms);

        // Pharmacist permissions
        $pharmacist = Role::where('name', 'pharmacist')->first();
        $pharmacistPerms = Permission::whereIn('module', ['dashboard', 'pharmacy'])
            ->pluck('id');
        $pharmacist->permissions()->sync($pharmacistPerms);

        // Patient permissions (read-only own data)
        $patient = Role::where('name', 'patient')->first();
        $patientPerms = Permission::whereIn('module', ['appointments', 'prescriptions', 'finance'])
            ->where('action', 'view')
            ->pluck('id');
        $patient->permissions()->sync($patientPerms);
    }
}
