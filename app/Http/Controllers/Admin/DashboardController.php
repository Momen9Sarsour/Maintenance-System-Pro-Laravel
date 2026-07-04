<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Equipment;
use App\Models\SparePart;
use App\Models\Invoice;
use App\Models\MaintenanceSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات مثل الصور
        $stats = [
            'active_work_orders' => WorkOrder::where('status', '!=', 'completed')->count(),
            'technicians' => User::where('role', 'technician')->where('is_active', true)->count(),
            'managers' => User::where('role', 'manager')->count(),
            'equipment' => Equipment::count(),
            'spare_parts' => SparePart::count(),
            'active_schedules' => MaintenanceSchedule::where('status', 'active')->count(),
            'clients' => User::where('role', 'client')->count(),
            'overdue_clients' => User::where('role', 'client')
                ->whereHas('clientWorkOrders', function ($query) {
                    $query->where('sla_status', 'overdue');
                })->count(),
            'pending_invoices' => Invoice::where('status', 'draft')->count(),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'total_income' => Invoice::where('status', 'paid')->sum('total_amount'),
        ];

        // أحدث أوامر العمل (مثل الصورة)
        $recentWorkOrders = WorkOrder::with(['client', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();

        // أداء الفنيين (مثل الصورة)
        $technicianPerformance = User::where('role', 'technician')
            ->with('technicianProfile')
            ->whereHas('technicianProfile')
            ->get();

        // المهام حسب الحالة
        $tasksByStatus = [
            'in_progress' => WorkOrder::where('status', 'in_progress')->count(),
            'completed' => WorkOrder::where('status', 'completed')->count(),
            'cancelled' => WorkOrder::where('status', 'cancelled')->count(),
            'on_hold' => WorkOrder::where('status', 'pending')->count(),
        ];

        // المهام حسب الأولوية
        $tasksByPriority = [
            'low' => WorkOrder::where('priority', 'low')->count(),
            'medium' => WorkOrder::where('priority', 'medium')->count(),
            'high' => WorkOrder::where('priority', 'high')->count(),
            'critical' => WorkOrder::where('priority', 'critical')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentWorkOrders',
            'technicianPerformance',
            'tasksByStatus',
            'tasksByPriority'
        ));
    }
}
