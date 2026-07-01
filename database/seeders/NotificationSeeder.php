<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // جلب المستخدمين
        $admin = User::where('role', 'admin')->first();
        $technicians = User::where('role', 'technician')->get();
        $clients = User::where('role', 'client')->get();

        // إذا ما في مستخدمين، نخرج
        if (!$admin) return;

        $notifications = [
            // إشعارات الـ Admin
            [
                'user_id' => $admin->id,
                'type' => 'info',
                'title' => 'New Assignment',
                'message' => 'You have been assigned to maintenance request: "Change spare"',
                'is_read' => false,
                'link' => '/admin/work-orders',
                'icon' => 'fas fa-tasks',
            ],
            [
                'user_id' => $admin->id,
                'type' => 'success',
                'title' => 'New Maintenance Request',
                'message' => 'Client "FRIZE Paolo" submitted a new request: "Change Filter"',
                'is_read' => false,
                'link' => '/admin/client-requests',
                'icon' => 'fas fa-check-circle',
            ],
            [
                'user_id' => $admin->id,
                'type' => 'warning',
                'title' => 'Request Approved',
                'message' => 'Your maintenance request "Change spare" has been approved.',
                'is_read' => false,
                'link' => '/admin/work-orders',
                'icon' => 'fas fa-exclamation-triangle',
            ],
            [
                'user_id' => $admin->id,
                'type' => 'error',
                'title' => 'تثبيت صيانة عاجلة',
                'message' => 'تم رصد ارتفاع في مستوى اهتزاز مضخة مياه التبريد في مجمع الدمام الصناعي. إجراء عرض فوري',
                'is_read' => false,
                'link' => '/admin/work-orders',
                'icon' => 'fas fa-exclamation-circle',
            ],

            // إشعارات الفنيين
            [
                'user_id' => $technicians[0]->id ?? $admin->id,
                'type' => 'info',
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned to work order: "Change element"',
                'is_read' => false,
                'link' => '/technician/tasks',
                'icon' => 'fas fa-clipboard-list',
            ],
            [
                'user_id' => $technicians[1]->id ?? $admin->id,
                'type' => 'info',
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned a new task: Quarterly HVAC Inspection.',
                'is_read' => true,
                'link' => '/technician/tasks',
                'icon' => 'fas fa-clipboard-list',
            ],

            // إشعارات العملاء
            [
                'user_id' => $clients[0]->id ?? $admin->id,
                'type' => 'success',
                'title' => 'Request Approved',
                'message' => 'Your maintenance request "Change spare" has been approved.',
                'is_read' => false,
                'link' => '/client/requests',
                'icon' => 'fas fa-check-circle',
            ],
            [
                'user_id' => $clients[1]->id ?? $admin->id,
                'type' => 'info',
                'title' => 'New Status Update',
                'message' => 'Your request "Change Filter" is now in progress.',
                'is_read' => false,
                'link' => '/client/requests',
                'icon' => 'fas fa-sync-alt',
            ],

            // إشعارات المدير
            [
                'user_id' => User::where('role', 'manager')->first()->id ?? $admin->id,
                'type' => 'warning',
                'title' => 'Overdue Work Order',
                'message' => 'Work order "Chiller Making Unusual Noise" is overdue by 3 days.',
                'is_read' => false,
                'link' => '/manager/work-orders',
                'icon' => 'fas fa-clock',
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        // إشعارات إضافية عشوائية للاختبار
        $types = ['info', 'success', 'warning', 'error'];
        $titles = [
            'System Update',
            'Maintenance Reminder',
            'New Inventory Alert',
            'Report Generated'
        ];

        for ($i = 0; $i < 10; $i++) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $types[array_rand($types)],
                'title' => $titles[array_rand($titles)],
                'message' => 'This is test notification number ' . ($i + 1),
                'is_read' => rand(0, 1),
                'link' => null,
                'icon' => 'fas fa-bell',
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
