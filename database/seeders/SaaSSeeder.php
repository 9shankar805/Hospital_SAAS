<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Clinic;

class SaaSSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Subscription Plans
        $starter = Plan::create([
            'name' => 'Starter Plan',
            'price_monthly' => 49.00,
            'price_yearly' => 490.00,
            'doctor_limit' => 5,
            'patient_limit' => 500,
            'features_json' => ['5 Doctors', '500 Patients', 'OPD Management', 'Standard Reports'],
            'status' => 'active'
        ]);

        $pro = Plan::create([
            'name' => 'Professional Plan',
            'price_monthly' => 149.00,
            'price_yearly' => 1490.00,
            'doctor_limit' => 25,
            'patient_limit' => 5000,
            'features_json' => ['25 Doctors', '5000 Patients', 'AI Assistant', 'Live Queue Display', 'Inventory & Pharmacy'],
            'status' => 'active'
        ]);

        $enterprise = Plan::create([
            'name' => 'Enterprise Plan',
            'price_monthly' => 499.00,
            'price_yearly' => 4990.00,
            'doctor_limit' => 999,
            'patient_limit' => 999999,
            'features_json' => ['Unlimited Doctors', 'Unlimited Patients', 'Multi-Hospital', 'Dedicated API', '24/7 Priority Support'],
            'status' => 'active'
        ]);

        // 2. Clinics / Tenants
        Clinic::create([
            'name' => 'Trustcare Clinic',
            'subdomain' => 'trustcare',
            'admin_email' => 'admin@trustcare.com',
            'phone' => '+1 800-555-0199',
            'plan_id' => $pro->id,
            'status' => 'active',
            'expires_at' => now()->addYear()
        ]);

        Clinic::create([
            'name' => 'Metro General Hospital',
            'subdomain' => 'metrogeneral',
            'admin_email' => 'contact@metrogeneral.org',
            'phone' => '+1 800-555-0210',
            'plan_id' => $enterprise->id,
            'status' => 'active',
            'expires_at' => now()->addYear()
        ]);

        Clinic::create([
            'name' => 'St. Jude Children Medical',
            'subdomain' => 'stjude',
            'admin_email' => 'info@stjude-med.org',
            'phone' => '+1 800-555-0322',
            'plan_id' => $starter->id,
            'status' => 'active',
            'expires_at' => now()->addMonths(6)
        ]);
    }
}
