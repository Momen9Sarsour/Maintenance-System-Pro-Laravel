<?php

namespace Database\Seeders;

use App\Models\MaintenanceSchedule;
use Illuminate\Database\Seeder;

class MaintenanceScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'title' => 'Quarterly HVAC Filter Replacement',
                'type' => 'preventive',
                'frequency' => 'quarterly',
                'interval_value' => 1,
                'start_date' => '2026-01-01',
                'next_due_date' => '2026-03-17',
                'last_completed_date' => '2025-12-15',
                'status' => 'active',
                'equipment_id' => 1,
            ],
            [
                'title' => 'Annual Chiller Overhaul',
                'type' => 'preventive',
                'frequency' => 'annual',
                'interval_value' => 1,
                'start_date' => '2026-01-15',
                'next_due_date' => '2026-06-15',
                'last_completed_date' => null,
                'status' => 'active',
                'equipment_id' => 4,
            ],
            [
                'title' => 'Monthly HVAC System Inspection',
                'type' => 'inspection',
                'frequency' => 'monthly',
                'interval_value' => 1,
                'start_date' => '2026-02-01',
                'next_due_date' => '2026-04-24',
                'last_completed_date' => '2026-03-24',
                'status' => 'active',
                'equipment_id' => 1,
            ],
            [
                'title' => 'Semi-Annual System Check',
                'type' => 'preventive',
                'frequency' => 'semi_annual',
                'interval_value' => 1,
                'start_date' => '2026-01-20',
                'next_due_date' => '2026-03-20',
                'last_completed_date' => null,
                'status' => 'overdue',
                'equipment_id' => 2,
            ],
            [
                'title' => 'Weekly Filter Cleaning',
                'type' => 'preventive',
                'frequency' => 'weekly',
                'interval_value' => 1,
                'start_date' => '2026-03-01',
                'next_due_date' => '2026-03-08',
                'last_completed_date' => '2026-03-01',
                'status' => 'completed',
                'equipment_id' => 3,
            ],
        ];

        foreach ($schedules as $schedule) {
            MaintenanceSchedule::create($schedule);
        }
    }
}
