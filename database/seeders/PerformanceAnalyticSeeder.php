<?php

namespace Database\Seeders;

use App\Models\PerformanceAnalytic;
use App\Models\User;
use Illuminate\Database\Seeder;

class PerformanceAnalyticSeeder extends Seeder
{
    public function run(): void
    {
        $technicians = User::where('role', 'technician')->get();

        $analytics = [
            [
                'user_id' => $technicians[0]->id ?? 1,
                'total_tasks' => 47,
                'completed_tasks' => 42,
                'pending_tasks' => 3,
                'cancelled_tasks' => 2,
                'on_hold_tasks' => 0,
                'completion_rate' => 89.36,
                'on_time_rate' => 92.00,
                'first_time_fix_rate' => 89.50,
                'avg_repair_time' => 108.00,
                'customer_rating' => 4.5,
                'period_start' => '2026-03-01',
                'period_end' => '2026-03-31',
            ],
            [
                'user_id' => $technicians[1]->id ?? 1,
                'total_tasks' => 32,
                'completed_tasks' => 28,
                'pending_tasks' => 2,
                'cancelled_tasks' => 2,
                'on_hold_tasks' => 0,
                'completion_rate' => 87.50,
                'on_time_rate' => 90.00,
                'first_time_fix_rate' => 85.00,
                'avg_repair_time' => 120.00,
                'customer_rating' => 4.2,
                'period_start' => '2026-03-01',
                'period_end' => '2026-03-31',
            ],
            [
                'user_id' => $technicians[2]->id ?? 1,
                'total_tasks' => 28,
                'completed_tasks' => 24,
                'pending_tasks' => 3,
                'cancelled_tasks' => 1,
                'on_hold_tasks' => 0,
                'completion_rate' => 85.71,
                'on_time_rate' => 88.00,
                'first_time_fix_rate' => 82.00,
                'avg_repair_time' => 95.00,
                'customer_rating' => 4.0,
                'period_start' => '2026-03-01',
                'period_end' => '2026-03-31',
            ],
        ];

        foreach ($analytics as $analytic) {
            PerformanceAnalytic::create($analytic);
        }
    }
}
