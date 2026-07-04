@extends('layouts.app')

@section('title', 'Admin Dashboard - MaintenancePro')

@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Complete system overview — all data visible')

@section('content')

<!-- ===== PERIOD FILTER ===== -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="btn-group" role="group" aria-label="Period filter">
        <button type="button" class="btn btn-sm btn-outline-secondary">Today</button>
        <button type="button" class="btn btn-sm btn-outline-secondary">7 Days</button>
        <button type="button" class="btn btn-sm btn-outline-secondary">30 Days</button>
        <button type="button" class="btn btn-sm btn-primary active">All Time</button>
    </div>
    <div>
        <span class="text-muted small">
            <i class="far fa-clock me-1"></i> Last updated: {{ now()->format('M d, Y H:i') }}
        </span>
    </div>
</div>

<!-- ===== STATS CARDS ===== -->
<div class="row g-3 g-md-4 mb-4">
    <!-- Active Work Orders -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Work Orders</div>
                    <div class="stat-number">{{ $stats['active_work_orders'] ?? 0 }}</div>
                    <span class="stat-change up"><i class="fas fa-arrow-up me-1"></i>+12%</span>
                </div>
                <div class="stat-icon primary">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Technicians -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Technicians</div>
                    <div class="stat-number">{{ $stats['technicians'] ?? 0 }}</div>
                    <span class="text-muted small">Active: {{ $stats['technicians'] ?? 0 }}</span>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-user-cog"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Managers -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Managers</div>
                    <div class="stat-number">{{ $stats['managers'] ?? 0 }}</div>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Equipment</div>
                    <div class="stat-number">{{ $stats['equipment'] ?? 0 }}</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-microchip"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Spare Parts -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Spare Parts</div>
                    <div class="stat-number">{{ $stats['spare_parts'] ?? 0 }}</div>
                </div>
                <div class="stat-icon purple">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Schedules -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Schedules</div>
                    <div class="stat-number">{{ $stats['active_schedules'] ?? 0 }}</div>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Clients</div>
                    <div class="stat-number">{{ $stats['clients'] ?? 0 }}</div>
                    @if(($stats['overdue_clients'] ?? 0) > 0)
                        <span class="stat-change down"><i class="fas fa-exclamation-circle me-1"></i>{{ $stats['overdue_clients'] }} overdue</span>
                    @else
                        <span class="text-muted small">All active</span>
                    @endif
                </div>
                <div class="stat-icon danger">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Income -->
    <div class="col-6 col-md-4 col-lg-3 animate-fade-in">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Income</div>
                    <div class="stat-number">${{ number_format($stats['total_income'] ?? 0, 2) }}</div>
                    <span class="text-muted small">{{ $stats['pending_invoices'] ?? 0 }} pending</span>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== CHARTS ROW ===== -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-line me-2 text-primary"></i> Task Overview</span>
                <span class="text-muted small">Last 7 days</span>
            </div>
            <div class="card-body">
                <canvas id="taskChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="fas fa-chart-pie me-2 text-primary"></i> Task Distribution</span>
            </div>
            <div class="card-body">
                <!-- Status Progress -->
                @php
                    $total = array_sum($tasksByStatus ?? []);
                    $maxTotal = max($total, 1);
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-circle text-primary" style="font-size: 10px;"></i> In Progress</span>
                        <span class="fw-bold">{{ $tasksByStatus['in_progress'] ?? 0 }}</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar bg-primary" style="width: {{ ($tasksByStatus['in_progress'] ?? 0) / $maxTotal * 100 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-circle text-success" style="font-size: 10px;"></i> Completed</span>
                        <span class="fw-bold">{{ $tasksByStatus['completed'] ?? 0 }}</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar bg-success" style="width: {{ ($tasksByStatus['completed'] ?? 0) / $maxTotal * 100 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-circle text-danger" style="font-size: 10px;"></i> Cancelled</span>
                        <span class="fw-bold">{{ $tasksByStatus['cancelled'] ?? 0 }}</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar bg-danger" style="width: {{ ($tasksByStatus['cancelled'] ?? 0) / $maxTotal * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-circle text-warning" style="font-size: 10px;"></i> On Hold</span>
                        <span class="fw-bold">{{ $tasksByStatus['on_hold'] ?? 0 }}</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar bg-warning" style="width: {{ ($tasksByStatus['on_hold'] ?? 0) / $maxTotal * 100 }}%"></div>
                    </div>
                </div>

                <hr>

                <!-- Priority Badges -->
                <div>
                    <div class="text-muted small mb-2">Priority Distribution</div>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge-priority low"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Low {{ $tasksByPriority['low'] ?? 0 }}</span>
                        <span class="badge-priority medium"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Medium {{ $tasksByPriority['medium'] ?? 0 }}</span>
                        <span class="badge-priority high"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> High {{ $tasksByPriority['high'] ?? 0 }}</span>
                        <span class="badge-priority critical"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Critical {{ $tasksByPriority['critical'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== RECENT WORK ORDERS TABLE ===== -->
<div class="card-custom mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-clock me-2 text-primary"></i> Recent Work Orders</span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-file-export me-1"></i> Export CSV
            </button>
            <button class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> New Work Order
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Date Request</th>
                        <th>Technician</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Price</th>
                        <th>Payment</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentWorkOrders ?? [] as $order)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $order->client->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $order->client->company_name ?? '' }}</small>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $order->assignedTo->name ?? '—' }}</td>
                        <td>
                            <span class="badge-status {{ str_replace('_', '-', $order->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-priority {{ $order->priority }}">
                                {{ ucfirst($order->priority) }}
                            </span>
                        </td>
                        <td>{{ $order->price ? '$' . number_format($order->price, 2) : '—' }}</td>
                        <td>
                            @if($order->invoice)
                                <span class="badge-status {{ $order->invoice->status }}">
                                    {{ ucfirst($order->invoice->status) }}
                                </span>
                            @else
                                <span class="badge-status unpaid">Unpaid</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            No work orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== TECHNICIAN PERFORMANCE ===== -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-bar me-2 text-primary"></i> Technician Performance</span>
                <span class="text-muted small">
                    <i class="fas fa-users me-1"></i> {{ $technicianPerformance->count() ?? 0 }} technicians
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($technicianPerformance ?? [] as $tech)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px; background: {{ ['#667eea', '#27ae60', '#f39c12', '#3498db', '#e74c3c', '#764ba2'][rand(0,5)] }}; color: white; font-weight: 700; font-size: 18px;">
                                    {{ substr($tech->name, 0, 2) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $tech->name }}</div>
                                <div class="small text-muted">{{ $tech->technicianProfile->specialization ?? 'Technician' }}</div>
                                <div class="mt-1 d-flex flex-wrap gap-1">
                                    <span class="badge bg-success">{{ $tech->technicianProfile->tasks_completed ?? 0 }} tasks</span>
                                    <span class="badge bg-warning text-dark">{{ $tech->technicianProfile->rating ?? 0 }}/5 ★</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $tech->technicianProfile->first_time_fix_rate ?? 0 }}%</div>
                                <small class="text-muted">Fix Rate</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="fas fa-user-cog fa-2x d-block mb-2"></i>
                        No technicians found
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="fas fa-bolt me-2 text-primary"></i> Quick Actions</span>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i> New Work Order
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-user-plus me-2"></i> Add Technician
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-file-invoice me-2"></i> Create Invoice
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-calendar-plus me-2"></i> Schedule Maintenance
                    </a>
                    <a href="#" class="btn btn-outline-secondary">
                        <i class="fas fa-file-alt me-2"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Task Chart =====
        const ctx = document.getElementById('taskChart')?.getContext('2d');
        if (ctx) {
            // بيانات الأيام السبعة الأخيرة
            const days = ['Mar 29', 'Mar 30', 'Mar 31', 'Apr 1', 'Apr 2', 'Apr 3', 'Apr 4'];
            const tasks = [12, 19, 15, 22, 18, 25, 20];

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Tasks',
                        data: tasks,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
