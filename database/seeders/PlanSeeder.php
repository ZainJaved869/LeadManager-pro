<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for getting started',
                'price' => 0,
                'currency' => 'USD',
                'interval' => 'monthly',
                'features' => ['5 Leads', '1 User', 'Basic Analytics'],
                'is_active' => true,
                'is_trial' => false,
                'trial_days' => 0,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'For small sales teams',
                'price' => 29.00,
                'currency' => 'USD',
                'interval' => 'monthly',
                'features' => ['50 Leads', '3 Users', 'Advanced Analytics', 'Email Marketing'],
                'is_active' => true,
                'is_trial' => true,
                'trial_days' => 14,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing businesses',
                'price' => 79.00,
                'currency' => 'USD',
                'interval' => 'monthly',
                'features' => ['500 Leads', '10 Users', 'AI Assistant', 'WhatsApp Integration', 'Email Marketing'],
                'is_active' => true,
                'is_trial' => true,
                'trial_days' => 14,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large organizations',
                'price' => 199.00,
                'currency' => 'USD',
                'interval' => 'monthly',
                'features' => ['Unlimited Leads', 'Unlimited Users', 'All Features', 'Priority Support', 'Custom Integrations'],
                'is_active' => true,
                'is_trial' => true,
                'trial_days' => 30,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}