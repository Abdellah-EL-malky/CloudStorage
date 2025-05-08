<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{

    public function run(): void
    {
        if (Plan::count() === 0) {
            Plan::create([
                'type' => 'Free',
                'price' => 0.00,
                'storage' => 1073741824,
            ]);

            Plan::create([
                'type' => 'Premium',
                'price' => 9.99,
                'storage' => 5368709120,
            ]);

            Plan::create([
                'type' => 'Business',
                'price' => 19.99,
                'storage' => 10737418240,
            ]);
        }
    }
}
