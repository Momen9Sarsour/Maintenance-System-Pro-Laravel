<?php

namespace Database\Seeders;

use App\Models\ClientRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientRequestSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $technicians = User::where('role', 'technician')->get();

        $requests = [
            [
                'title' => 'Change spare',
                'description' => 'طلب تغيير قطعة غيار في وحدة التكييف',
                'client_id' => $clients[0]->id ?? 1,
                'equipment_name' => 'وحدة تكييف مركزية',
                'location' => 'برج جدة التجاري',
                'preferred_date' => '2026-04-16',
                'additional_notes' => 'يرجى إحضار القطعة المناسبة',
                'status' => 'approved',
                'assigned_technician_id' => $technicians[0]->id ?? null,
                'approved_at' => now(),
            ],
            [
                'title' => 'Change Filter',
                'description' => 'تغيير فلتر الهواء',
                'client_id' => $clients[1]->id ?? 1,
                'equipment_name' => 'Air Handler Unit',
                'location' => 'Building A',
                'preferred_date' => '2026-04-20',
                'additional_notes' => null,
                'status' => 'pending',
                'assigned_technician_id' => null,
                'approved_at' => null,
            ],
            [
                'title' => 'Chiller Making Unusual Noise',
                'description' => 'مكيف يصدر صوت غير طبيعي',
                'client_id' => $clients[4]->id ?? 1,
                'equipment_name' => 'Chiller Unit',
                'location' => 'Building B',
                'preferred_date' => '2026-04-02',
                'additional_notes' => 'الصوت مسموع منذ 3 أيام',
                'status' => 'approved',
                'assigned_technician_id' => $technicians[2]->id ?? null,
                'approved_at' => now()->subDays(1),
            ],
        ];

        foreach ($requests as $request) {
            ClientRequest::create($request);
        }
    }
}
