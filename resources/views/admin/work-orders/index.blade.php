    @extends('layouts.app')

    @section('title', 'Work Orders - MaintenancePro')
    @section('page-title', 'Work Orders')
    @section('page-subtitle', 'Manage all maintenance work orders')

    @section('content')

    <!-- ===== FILTERS & SEARCH ===== -->
    <div class="card-custom mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Search</label>
                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Client or technician...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Priority</label>
                    <select class="form-select form-select-sm" id="priorityFilter">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Date From</label>
                    <input type="date" class="form-control form-control-sm" id="dateFrom">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Date To</label>
                    <input type="date" class="form-control form-control-sm" id="dateTo">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-secondary w-100" id="clearFilters" title="Clear Filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card-custom">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>
                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                Work Orders
                <span class="badge bg-primary ms-2" id="totalCount">{{ $workOrders->count() }}</span>
            </span>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" id="refreshTable" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="exportBtn">
                    <i class="fas fa-file-export me-1"></i> Export
                </button>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-1"></i> New Work Order
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0" id="workOrdersTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Title</th>
                            <th>Client</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Price</th>
                            <th class="text-end" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($workOrders as $index => $order)
                        <tr data-id="{{ $order->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($order->title, 30) }}</div>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($order->description, 40) }}</small>
                            </td>
                            <td>{{ $order->client->name ?? 'N/A' }}</td>
                            <td>{{ $order->assignedTo->name ?? '—' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle status-dropdown"
                                            data-id="{{ $order->id }}"
                                            data-current-status="{{ $order->status }}"
                                            style="border: none; padding: 0; background: transparent;"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu status-menu" data-id="{{ $order->id }}">
                                        <li><a class="dropdown-item status-item" data-status="pending" href="#">
                                            <span class="badge-status pending">Pending</span>
                                        </a></li>
                                        <li><a class="dropdown-item status-item" data-status="in_progress" href="#">
                                            <span class="badge-status in-progress">In Progress</span>
                                        </a></li>
                                        <li><a class="dropdown-item status-item" data-status="completed" href="#">
                                            <span class="badge-status completed">Completed</span>
                                        </a></li>
                                        <li><a class="dropdown-item status-item" data-status="cancelled" href="#">
                                            <span class="badge-status cancelled">Cancelled</span>
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle priority-dropdown"
                                            data-id="{{ $order->id }}"
                                            data-current-priority="{{ $order->priority }}"
                                            style="border: none; padding: 0; background: transparent;"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="priority-badge priority-{{ $order->priority }}">
                                            {{ ucfirst($order->priority) }}
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu priority-menu" data-id="{{ $order->id }}">
                                        <li><a class="dropdown-item priority-item" data-priority="low" href="#">
                                            <span class="badge-priority low">Low</span>
                                        </a></li>
                                        <li><a class="dropdown-item priority-item" data-priority="medium" href="#">
                                            <span class="badge-priority medium">Medium</span>
                                        </a></li>
                                        <li><a class="dropdown-item priority-item" data-priority="high" href="#">
                                            <span class="badge-priority high">High</span>
                                        </a></li>
                                        <li><a class="dropdown-item priority-item" data-priority="critical" href="#">
                                            <span class="badge-priority critical">Critical</span>
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                @php
                                    $dueDate = \Carbon\Carbon::parse($order->due_date);
                                    $isOverdue = $dueDate->isPast() && $order->status !== 'completed';
                                @endphp
                                <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                    {{ $dueDate->format('M d, Y') }}
                                    @if($isOverdue)
                                        <i class="fas fa-exclamation-circle text-danger ms-1" title="Overdue"></i>
                                    @endif
                                </span>
                            </td>
                            <td>{{ $order->price ? '$' . number_format($order->price, 2) : '—' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary view-btn" data-id="{{ $order->id }}" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success edit-btn" data-id="{{ $order->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger delete-btn" data-id="{{ $order->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x d-block mb-3"></i>
                                <h5>No Work Orders Found</h5>
                                <p class="mb-0">Click "New Work Order" to create one.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ===== MODAL: CREATE WORK ORDER ===== -->
    <!-- ============================================ -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
                <div class="modal-header" style="border-color: var(--border-color);">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary me-2"></i> Create New Work Order
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. Quarterly HVAC Inspection" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Describe the work order details..." required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                                <select class="form-select" name="client_id" required>
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Assigned To</label>
                                <select class="form-select" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Equipment</label>
                                <select class="form-select" name="equipment_id">
                                    <option value="">Select Equipment</option>
                                    @foreach($equipment as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                                <select class="form-select" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="due_date" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price</label>
                                <input type="number" class="form-control" name="price" placeholder="0.00" step="0.01" min="0">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-color: var(--border-color);">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="createSubmit">
                            <i class="fas fa-spinner fa-spin d-none" id="createSpinner"></i>
                            <span id="createText">Create Work Order</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ===== MODAL: VIEW WORK ORDER ===== -->
    <!-- ============================================ -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
                <div class="modal-header" style="border-color: var(--border-color);">
                    <h5 class="modal-title">
                        <i class="fas fa-eye text-primary me-2"></i> Work Order Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<!-- ============================================ -->
<!-- ===== MODAL: EDIT WORK ORDER ===== -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-edit text-success me-2"></i> Edit Work Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="editTitle" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="editDescription" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                            <select class="form-select" name="client_id" id="editClient" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Assigned To</label>
                            <select class="form-select" name="assigned_to" id="editAssigned">
                                <option value="">Unassigned</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Equipment</label>
                            <select class="form-select" name="equipment_id" id="editEquipment">
                                <option value="">Select Equipment</option>
                                @foreach($equipment as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" name="priority" id="editPriority" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="editStatus" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="due_date" id="editDueDate" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Price</label>
                            <input type="number" class="form-control" name="price" id="editPrice" placeholder="0.00" step="0.01" min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="editSubmit">
                        <i class="fas fa-spinner fa-spin d-none" id="editSpinner"></i>
                        <span id="editText">Update Work Order</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- ============================================ -->
    <!-- ===== MODAL: DELETE WORK ORDER ===== -->
    <!-- ============================================ -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
                <div class="modal-header" style="border-color: var(--border-color);">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteId">
                    <p class="mb-0">Are you sure you want to delete this work order?</p>
                    <small class="text-muted">This action cannot be undone.</small>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteConfirm">
                        <i class="fas fa-spinner fa-spin d-none" id="deleteSpinner"></i>
                        <span id="deleteText">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Toast Container ===== -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastContainer"></div>
    </div>

    @endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';

        // ========================================
        // ===== CSRF Token =====
        // ========================================
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // ========================================
        // ===== Toast Notification =====
        // ========================================
        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-success',
                error: 'bg-danger',
                warning: 'bg-warning',
                info: 'bg-info'
            };

            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white ${colors[type] || 'bg-primary'} border-0`;
            toast.role = 'alert';
            toast.ariaLive = 'assertive';
            toast.ariaAtomic = 'true';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            toastContainer.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        // ========================================
        // ===== UPDATE Status & Priority =====
        // ========================================

        // Status Update - Global Handler
        document.addEventListener('click', function(e) {
            const statusItem = e.target.closest('.status-item');
            if (statusItem) {
                e.preventDefault();
                const newStatus = statusItem.dataset.status;
                const dropdown = statusItem.closest('.dropdown');
                const orderId = dropdown.querySelector('.status-dropdown').dataset.id;
                const button = dropdown.querySelector('.status-dropdown');
                const badge = button.querySelector('.status-badge');

                fetch(`/admin/work-orders/toggle-status/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        badge.className = `status-badge status-${newStatus}`;
                        badge.textContent = newStatus.replace('_', ' ').toUpperCase();
                        button.dataset.currentStatus = newStatus;
                        // Close dropdown
                        const bsDropdown = bootstrap.Dropdown.getInstance(button);
                        if (bsDropdown) bsDropdown.hide();
                        refreshTable();
                    } else {
                        showToast(data.message || 'Update failed', 'error');
                        refreshTable();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error: ' + (error.message || 'Unknown error'), 'error');
                    refreshTable();
                });
            }
        });

        // Priority Update - Global Handler
        document.addEventListener('click', function(e) {
            const priorityItem = e.target.closest('.priority-item');
            if (priorityItem) {
                e.preventDefault();
                const newPriority = priorityItem.dataset.priority;
                const dropdown = priorityItem.closest('.dropdown');
                const orderId = dropdown.querySelector('.priority-dropdown').dataset.id;
                const button = dropdown.querySelector('.priority-dropdown');
                const badge = button.querySelector('.priority-badge');

                fetch(`/admin/work-orders/toggle-priority/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ priority: newPriority })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        badge.className = `priority-badge priority-${newPriority}`;
                        badge.textContent = newPriority.toUpperCase();
                        button.dataset.currentPriority = newPriority;
                        // Close dropdown
                        const bsDropdown = bootstrap.Dropdown.getInstance(button);
                        if (bsDropdown) bsDropdown.hide();
                        refreshTable();
                    } else {
                        showToast(data.message || 'Update failed', 'error');
                        refreshTable();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error: ' + (error.message || 'Unknown error'), 'error');
                    refreshTable();
                });
            }
        });

        // ========================================
        // ===== CREATE =====
        // ========================================
        const createForm = document.getElementById('createForm');
        const createSubmit = document.getElementById('createSubmit');
        const createSpinner = document.getElementById('createSpinner');
        const createText = document.getElementById('createText');

        createForm?.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear old errors
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            this.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            createSpinner.classList.remove('d-none');
            createText.textContent = 'Creating...';
            createSubmit.disabled = true;

            const formData = new FormData(this);

            fetch('{{ route("admin.work-orders.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
                    if (modal) modal.hide();
                    showToast(data.message, 'success');
                    refreshTable();
                    createForm.reset();
                } else {
                    if (data.errors) {
                        for (const [key, errors] of Object.entries(data.errors)) {
                            const input = createForm.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.closest('.col-12, .col-md-6')?.querySelector('.invalid-feedback');
                                if (feedback) feedback.textContent = errors[0];
                            }
                        }
                    }
                    showToast('Validation error occurred', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            })
            .finally(() => {
                createSpinner.classList.add('d-none');
                createText.textContent = 'Create Work Order';
                createSubmit.disabled = false;
            });
        });

        // Reset form on modal close
        document.getElementById('createModal')?.addEventListener('hidden.bs.modal', function() {
            createForm.reset();
            createForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            createForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        });

        // ========================================
        // ===== VIEW =====
        // ========================================
        document.addEventListener('click', function(e) {
            const viewBtn = e.target.closest('.view-btn');
            if (viewBtn) {
                const id = viewBtn.dataset.id;
                const modal = document.getElementById('viewModal');
                const content = document.getElementById('viewContent');

                content.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                fetch(`/admin/work-orders/${id}/view`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const order = data.data;
                        content.innerHTML = `
                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="fw-bold">${order.title}</h5>
                                    <p class="mb-0">${order.description}</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Client</small>
                                        <div class="fw-semibold">${order.client?.name || 'N/A'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Assigned To</small>
                                        <div class="fw-semibold">${order.assigned_to?.name || 'Unassigned'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Equipment</small>
                                        <div class="fw-semibold">${order.equipment?.name || 'N/A'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Priority</small>
                                        <div><span class="badge-priority ${order.priority}">${order.priority.toUpperCase()}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Status</small>
                                        <div><span class="badge-status ${order.status.replace('_', '-')}">${order.status.replace('_', ' ').toUpperCase()}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Due Date</small>
                                        <div class="fw-semibold">${new Date(order.due_date).toLocaleDateString()}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Price</small>
                                        <div class="fw-semibold">${order.price ? '$' + Number(order.price).toFixed(2) : 'N/A'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Created By</small>
                                        <div class="fw-semibold">${order.created_by?.name || 'N/A'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Created At</small>
                                        <div class="fw-semibold">${new Date(order.created_at).toLocaleString()}</div>
                                    </div>
                                </div>
                                ${order.invoice ? `
                                <div class="col-12">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Invoice</small>
                                        <div class="fw-semibold">#${order.invoice.invoice_number} - $${Number(order.invoice.total_amount).toFixed(2)}</div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    content.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                            <p>Failed to load work order details</p>
                        </div>
                    `;
                });
            }
        });

// ========================================
// ===== EDIT =====
// ========================================
let editModalInstance = null;

document.addEventListener('click', function(e) {
    const editBtn = e.target.closest('.edit-btn');
    if (editBtn) {
        const id = editBtn.dataset.id;
        const modalElement = document.getElementById('editModal');

        // Reset form
        const form = document.getElementById('editForm');
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        document.getElementById('editId').value = id;

        if (!editModalInstance) {
            editModalInstance = new bootstrap.Modal(modalElement);
        }
        editModalInstance.show();

        // Load data
        fetch(`/admin/work-orders/${id}/view`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const order = data.data;

                // Title & Description
                document.getElementById('editTitle').value = order.title;
                document.getElementById('editDescription').value = order.description;

                // Client
                document.getElementById('editClient').value = order.client_id;

                // ✅ FIX: Assigned To - تأكد من القيمة
                const assignedTo = order.assigned_to || '';
                document.getElementById('editAssigned').value = assignedTo;

                // Equipment
                document.getElementById('editEquipment').value = order.equipment_id || '';

                // Priority & Status
                document.getElementById('editPriority').value = order.priority;
                document.getElementById('editStatus').value = order.status;

                // ✅ FIX: Format date properly for input type="date"
                if (order.due_date) {
                    const dueDate = new Date(order.due_date);
                    // تأكد من أن التاريخ صحيح
                    if (!isNaN(dueDate.getTime())) {
                        const year = dueDate.getFullYear();
                        const month = String(dueDate.getMonth() + 1).padStart(2, '0');
                        const day = String(dueDate.getDate()).padStart(2, '0');
                        document.getElementById('editDueDate').value = `${year}-${month}-${day}`;
                    }
                }

                // Price
                document.getElementById('editPrice').value = order.price || '';

                // ✅ Log for debugging
                console.log('📝 Edit Data Loaded:', {
                    id: order.id,
                    title: order.title,
                    client_id: order.client_id,
                    assigned_to: assignedTo,
                    priority: order.priority,
                    status: order.status,
                    due_date: document.getElementById('editDueDate').value
                });
            } else {
                showToast('Failed to load work order data', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading edit data:', error);
            showToast('Failed to load work order data', 'error');
        });
    }
});

const editForm = document.getElementById('editForm');
const editSubmit = document.getElementById('editSubmit');
const editSpinner = document.getElementById('editSpinner');
const editText = document.getElementById('editText');

editForm?.addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('editId').value;

    // Clear old errors
    this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    this.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

    editSpinner.classList.remove('d-none');
    editText.textContent = 'Updating...';
    editSubmit.disabled = true;

    const formData = new FormData(this);

    // ✅ Debug: Log form data
    console.log('📤 Submitting Edit Form:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch(`/admin/work-orders/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (editModalInstance) {
                editModalInstance.hide();
            }
            showToast(data.message, 'success');
            refreshTable();
        } else {
            if (data.errors) {
                for (const [key, errors] of Object.entries(data.errors)) {
                    const input = editForm.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.closest('.col-12, .col-md-6')?.querySelector('.invalid-feedback');
                        if (feedback) feedback.textContent = errors[0];
                    }
                }
                showToast('Validation error occurred', 'error');
            } else {
                showToast(data.message || 'Update failed', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error: ' + (error.message || 'Unknown error'), 'error');
    })
    .finally(() => {
        editSpinner.classList.add('d-none');
        editText.textContent = 'Update Work Order';
        editSubmit.disabled = false;
    });
});

// Reset edit form on modal close
document.getElementById('editModal')?.addEventListener('hidden.bs.modal', function() {
    editForm.reset();
    editForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    editForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
});

        // ========================================
        // ===== DELETE =====
        // ========================================
        let deleteModalInstance = null;

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                const id = deleteBtn.dataset.id;
                document.getElementById('deleteId').value = id;

                const modalElement = document.getElementById('deleteModal');
                if (!deleteModalInstance) {
                    deleteModalInstance = new bootstrap.Modal(modalElement);
                }
                deleteModalInstance.show();
            }
        });

        document.getElementById('deleteConfirm')?.addEventListener('click', function() {
            const id = document.getElementById('deleteId').value;
            const spinner = document.getElementById('deleteSpinner');
            const text = document.getElementById('deleteText');

            spinner.classList.remove('d-none');
            text.textContent = 'Deleting...';
            this.disabled = true;

            fetch(`/admin/work-orders/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (deleteModalInstance) {
                        deleteModalInstance.hide();
                    }
                    showToast(data.message, 'success');
                    refreshTable();
                } else {
                    showToast(data.message || 'Delete failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error: ' + (error.message || 'Unknown error'), 'error');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                text.textContent = 'Delete';
                this.disabled = false;
            });
        });

        // ========================================
        // ===== REFRESH Table =====
        // ========================================
        function refreshTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </td>
                </tr>
            `;

            fetch('{{ route("admin.work-orders.fetch") }}', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const orders = data.data;
                    document.getElementById('totalCount').textContent = orders.length;

                    if (orders.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x d-block mb-3"></i>
                                    <h5>No Work Orders Found</h5>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    let html = '';
                    orders.forEach((order, index) => {
                        const dueDate = new Date(order.due_date);
                        const isOverdue = dueDate < new Date() && order.status !== 'completed';

                        html += `
                            <tr data-id="${order.id}">
                                <td>${index + 1}</td>
                                <td>
                                    <div class="fw-semibold">${order.title.length > 30 ? order.title.substring(0, 30) + '...' : order.title}</div>
                                    <small class="text-muted">${order.description.length > 40 ? order.description.substring(0, 40) + '...' : order.description}</small>
                                </td>
                                <td>${order.client?.name || 'N/A'}</td>
                                <td>${order.assigned_to?.name || '—'}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle status-dropdown"
                                                data-id="${order.id}"
                                                data-current-status="${order.status}"
                                                style="border: none; padding: 0; background: transparent;"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <span class="status-badge status-${order.status}">
                                                ${order.status.replace('_', ' ').toUpperCase()}
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu status-menu" data-id="${order.id}">
                                            <li><a class="dropdown-item status-item" data-status="pending" href="#">
                                                <span class="badge-status pending">Pending</span>
                                            </a></li>
                                            <li><a class="dropdown-item status-item" data-status="in_progress" href="#">
                                                <span class="badge-status in-progress">In Progress</span>
                                            </a></li>
                                            <li><a class="dropdown-item status-item" data-status="completed" href="#">
                                                <span class="badge-status completed">Completed</span>
                                            </a></li>
                                            <li><a class="dropdown-item status-item" data-status="cancelled" href="#">
                                                <span class="badge-status cancelled">Cancelled</span>
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle priority-dropdown"
                                                data-id="${order.id}"
                                                data-current-priority="${order.priority}"
                                                style="border: none; padding: 0; background: transparent;"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <span class="priority-badge priority-${order.priority}">
                                                ${order.priority.toUpperCase()}
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu priority-menu" data-id="${order.id}">
                                            <li><a class="dropdown-item priority-item" data-priority="low" href="#">
                                                <span class="badge-priority low">Low</span>
                                            </a></li>
                                            <li><a class="dropdown-item priority-item" data-priority="medium" href="#">
                                                <span class="badge-priority medium">Medium</span>
                                            </a></li>
                                            <li><a class="dropdown-item priority-item" data-priority="high" href="#">
                                                <span class="badge-priority high">High</span>
                                            </a></li>
                                            <li><a class="dropdown-item priority-item" data-priority="critical" href="#">
                                                <span class="badge-priority critical">Critical</span>
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="${isOverdue ? 'text-danger fw-bold' : ''}">
                                    ${dueDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                    ${isOverdue ? '<i class="fas fa-exclamation-circle text-danger ms-1" title="Overdue"></i>' : ''}
                                </td>
                                <td>${order.price ? '$' + Number(order.price).toFixed(2) : '—'}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary view-btn" data-id="${order.id}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success edit-btn" data-id="${order.id}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger delete-btn" data-id="${order.id}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });

                    tbody.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error refreshing table:', error);
                showToast('Failed to refresh table', 'error');
            });
        }

        document.getElementById('refreshTable')?.addEventListener('click', refreshTable);

        // ========================================
        // ===== FILTERS =====
        // ========================================
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');

        function filterTable() {
            const search = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            const priority = priorityFilter.value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                if (row.querySelector('.text-center')) return;

                let show = true;
                const cells = row.querySelectorAll('td');
                if (cells.length < 9) return;

                if (search) {
                    const text = row.textContent.toLowerCase();
                    if (!text.includes(search)) show = false;
                }

                if (status && show) {
                    const statusBtn = cells[4]?.querySelector('.status-dropdown');
                    if (statusBtn && statusBtn.dataset.currentStatus !== status) show = false;
                }

                if (priority && show) {
                    const priorityBtn = cells[5]?.querySelector('.priority-dropdown');
                    if (priorityBtn && priorityBtn.dataset.currentPriority !== priority) show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        [searchInput, statusFilter, priorityFilter, dateFrom, dateTo].forEach(el => {
            el?.addEventListener('change', filterTable);
            el?.addEventListener('keyup', filterTable);
        });

        document.getElementById('clearFilters')?.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            priorityFilter.value = '';
            dateFrom.value = '';
            dateTo.value = '';
            filterTable();
        });

        // ========================================
        // ===== Export =====
        // ========================================
        document.getElementById('exportBtn')?.addEventListener('click', function() {
            showToast('Export functionality coming soon!', 'info');
        });

        // ========================================
        // ===== Additional CSS =====
        // ========================================
        const style = document.createElement('style');
        style.textContent = `
            .status-badge, .priority-badge {
                padding: 4px 14px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                display: inline-block;
                cursor: pointer;
                transition: all 0.2s;
            }

            .status-badge:hover, .priority-badge:hover {
                opacity: 0.8;
                transform: scale(0.95);
            }

            .status-badge.status-pending { background: #fff3cd; color: #856404; }
            .status-badge.status-in_progress { background: #cce5ff; color: #004085; }
            .status-badge.status-completed { background: #d4edda; color: #155724; }
            .status-badge.status-cancelled { background: #f8d7da; color: #721c24; }

            .priority-badge.priority-low { background: #e9ecef; color: #6c757d; }
            .priority-badge.priority-medium { background: #cce5ff; color: #004085; }
            .priority-badge.priority-high { background: #fff3cd; color: #856404; }
            .priority-badge.priority-critical { background: #f8d7da; color: #721c24; }

            .dropdown-menu .dropdown-item:hover {
                background: var(--hover-bg);
            }

            .dropdown-toggle::after {
                display: none !important;
            }

            .dropdown-toggle {
                padding: 0 !important;
                background: transparent !important;
                border: none !important;
            }

            .dropdown-toggle:hover .status-badge,
            .dropdown-toggle:hover .priority-badge {
                transform: scale(0.95);
                opacity: 0.8;
            }

            /* Dark mode fixes */
            [data-bs-theme="dark"] .bg-light {
                background-color: var(--bg-body) !important;
            }
            [data-bs-theme="dark"] .bg-light .text-muted {
                color: var(--text-secondary) !important;
            }
            [data-bs-theme="dark"] .status-badge.status-pending { background: #4a3a1a; color: #ffd700; }
            [data-bs-theme="dark"] .status-badge.status-in_progress { background: #1a2a4a; color: #66b0ff; }
            [data-bs-theme="dark"] .status-badge.status-completed { background: #1a3a2a; color: #66ff88; }
            [data-bs-theme="dark"] .status-badge.status-cancelled { background: #3a1a1a; color: #ff6666; }
            [data-bs-theme="dark"] .priority-badge.priority-low { background: #2a2a2a; color: #aaa; }
            [data-bs-theme="dark"] .priority-badge.priority-medium { background: #1a2a4a; color: #66b0ff; }
            [data-bs-theme="dark"] .priority-badge.priority-high { background: #4a3a1a; color: #ffd700; }
            [data-bs-theme="dark"] .priority-badge.priority-critical { background: #3a1a1a; color: #ff6666; }
        `;
        document.head.appendChild(style);

        // ========================================
        // ===== Initial Load =====
        // ========================================
        console.log('📋 Work Orders Page Loaded Successfully!');
    });
    </script>
    @endpush
