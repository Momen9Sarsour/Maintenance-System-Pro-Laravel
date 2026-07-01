<?php

namespace Database\Seeders;

use App\Models\GPSTracking;
use App\Models\User;
use Illuminate\Database\Seeder;

class GPSTrackingSeeder extends Seeder
{
    public function run(): void
    {
        // جلب الفنيين
        $technicians = User::where('role', 'technician')->get();

        // إذا ما في فنيين، نخرج
        if ($technicians->isEmpty()) {
            $this->command->info('No technicians found, skipping GPS tracking seeding.');
            return;
        }

        $trackings = [
            [
                'user_id' => $technicians[0]->id,
                'work_order_id' => null,
                'latitude' => 21.543300,
                'longitude' => 39.172800,
                'accuracy' => 10.5,
                'tracked_at' => now()->subHours(4),
            ],
            [
                'user_id' => $technicians[1]->id ?? $technicians[0]->id,
                'work_order_id' => null,
                'latitude' => 26.420700,
                'longitude' => 50.088800,
                'accuracy' => 8.2,
                'tracked_at' => now()->subHours(2),
            ],
            [
                'user_id' => $technicians[2]->id ?? $technicians[0]->id,
                'work_order_id' => null,
                'latitude' => 21.500000,
                'longitude' => 39.200000,
                'accuracy' => 12.0,
                'tracked_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($trackings as $tracking) {
            GPSTracking::create($tracking);
        }

        $this->command->info('GPS Trackings seeded successfully!');
    }
}
