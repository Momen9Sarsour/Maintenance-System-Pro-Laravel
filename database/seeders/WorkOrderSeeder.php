<?php

namespace Database\Seeders;

use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $technicians = User::where('role', 'technician')->get();
        $admin = User::where('role', 'admin')->first();

        $workOrders = [
            [
                'title' => 'Change spare',
                'description' => 'تغيير قطعة غيار في وحدة التكييف',
                'priority' => 'medium',
                'status' => 'in_progress',
                'sla_status' => 'on_time',
                'assigned_to' => $technicians[0]->id ?? null,
                'created_by' => $admin->id ?? 1,
                'client_id' => $clients[0]->id ?? 1,
                'equipment_id' => 1,
                'due_date' => '2026-04-20 18:00:00',
                'price' => null,
            ],
            [
                'title' => 'Change element',
                'description' => 'تغيير عنصر في نظام التبريد',
                'priority' => 'high',
                'status' => 'pending',
                'sla_status' => 'due_soon',
                'assigned_to' => null,
                'created_by' => $admin->id ?? 1,
                'client_id' => $clients[0]->id ?? 1,
                'equipment_id' => 1,
                'due_date' => '2026-04-25 18:00:00',
                'price' => null,
            ],
            [
                'title' => 'Chiller Making Unusual Noise',
                'description' => 'مكيف يصدر صوت غير طبيعي يحتاج فحص',
                'priority' => 'critical',
                'status' => 'pending',
                'sla_status' => 'overdue',
                'assigned_to' => null,
                'created_by' => $admin->id ?? 1,
                'client_id' => $clients[4]->id ?? 1,
                'equipment_id' => 4,
                'due_date' => '2026-04-01 18:00:00',
                'price' => '6670.00',
            ],
            [
                'title' => 'Air Handler Unit Not Cooling',
                'description' => 'وحدة معالجة الهواء لا تبرد بشكل كافي',
                'priority' => 'high',
                'status' => 'in_progress',
                'sla_status' => 'on_time',
                'assigned_to' => $technicians[2]->id ?? null,
                'created_by' => $admin->id ?? 1,
                'client_id' => $clients[5]->id ?? 1,
                'equipment_id' => 3,
                'due_date' => '2026-04-15 18:00:00',
                'price' => null,
            ],
            [
                'title' => 'Quarterly HVAC Inspection',
                'description' => 'فحص دوري لنظام التكييف',
                'priority' => 'low',
                'status' => 'completed',
                'sla_status' => 'on_time',
                'assigned_to' => $technicians[1]->id ?? null,
                'created_by' => $admin->id ?? 1,
                'client_id' => $clients[3]->id ?? 1,
                'equipment_id' => 2,
                'due_date' => '2026-03-20 18:00:00',
                'completed_at' => '2026-03-19 15:30:00',
                'price' => '1650.00',
            ],
            [
                'title' => 'Change Filter',
                'description' => 'تغيير فلتر الهواء في الوحدة',
                'priority' => 'medium',
                'status' => 'pending',
                'sla_status' => 'on_time',
                'assigned_to' => null,
                'created_by' => $admin->id ?? 1,
                'client_id' => $clients[2]->id ?? 1,
                'equipment_id' => 3,
                'due_date' => '2026-04-30 18:00:00',
                'price' => null,
            ],
        ];

        foreach ($workOrders as $order) {
            WorkOrder::create($order);
        }
    }
}
