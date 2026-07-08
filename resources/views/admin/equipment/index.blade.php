@extends('layouts.app')

@section('title', 'Equipment - MaintenancePro')
@section('page-title', 'Equipment')
@section('page-subtitle', 'Manage all equipment and assets')

@section('content')

<!-- ===== FILTERS & SEARCH ===== -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Search</label>
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Name, model, or serial...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="operational">Operational</option>
                    <option value="maintenance">Under Maintenance</option>
                    <option value="out_of_service">Out of Service</option>
                    <option value="retired">Retired</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Manufacturer</label>
                <input type="text" class="form-control form-control-sm" id="manufacturerFilter" placeholder="e.g. Bosch">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Location</label>
                <input type="text" class="form-control form-control-sm" id="locationFilter" placeholder="e.g. Jeddah">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-secondary w-100" id="clearFilters" title="Clear Filters">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EQUIPMENT CARDS ===== -->
<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>
            <i class="fas fa-microchip me-2 text-primary"></i>
            Equipment
            <span class="badge bg-primary ms-2" id="totalCount">{{ $equipment->count() }}</span>
        </span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" id="refreshTable" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> New Equipment
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4" id="cardsContainer">
            @forelse($equipment as $item)
            <div class="col-md-6 col-lg-4 equipment-card" data-id="{{ $item->id }}">
                <div class="card h-100 border-0 shadow-sm rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color); transition: all 0.3s;">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; font-weight: 700; font-size: 20px; flex-shrink: 0;">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);">{{ Str::limit($item->name, 25) }}</h6>
                                    <small class="text-muted">{{ $item->model ?? 'No model' }}</small>
                                </div>
                            </div>
                            <!-- Status Badge -->
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle status-dropdown"
                                        data-id="{{ $item->id }}"
                                        data-current-status="{{ $item->status }}"
                                        style="border: none; padding: 0; background: transparent;"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <span class="status-badge status-{{ $item->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </button>
                                <ul class="dropdown-menu status-menu" data-id="{{ $item->id }}">
                                    <li><a class="dropdown-item status-item" data-status="operational" href="#">
                                        <span class="badge-status operational">Operational</span>
                                    </a></li>
                                    <li><a class="dropdown-item status-item" data-status="maintenance" href="#">
                                        <span class="badge-status maintenance">Under Maintenance</span>
                                    </a></li>
                                    <li><a class="dropdown-item status-item" data-status="out_of_service" href="#">
                                        <span class="badge-status out_of_service">Out of Service</span>
                                    </a></li>
                                    <li><a class="dropdown-item status-item" data-status="retired" href="#">
                                        <span class="badge-status retired">Retired</span>
                                    </a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-barcode text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $item->serial_number }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-building text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $item->manufacturer ?? 'Unknown' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-map-pin text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $item->location ?? 'No location' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-user-cog text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $item->assignedTechnician->name ?? 'Unassigned' }}</span>
                            </div>
                        </div>

                        <!-- Installation & Warranty -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary); font-size: 13px;">
                                        {{ $item->installation_date ? \Carbon\Carbon::parse($item->installation_date)->format('M d, Y') : '—' }}
                                    </div>
                                    <small class="text-muted" style="font-size: 10px;">Installed</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary); font-size: 13px;">
                                        {{ $item->warranty_expiry ? \Carbon\Carbon::parse($item->warranty_expiry)->format('M d, Y') : '—' }}
                                    </div>
                                    <small class="text-muted" style="font-size: 10px;">Warranty</small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex align-items-center justify-content-end pt-2" style="border-top: 1px solid var(--border-color);">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary view-btn" data-id="{{ $item->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success edit-btn" data-id="{{ $item->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger delete-btn" data-id="{{ $item->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-microchip fa-3x d-block mb-3"></i>
                <h5>No Equipment Found</h5>
                <p class="mb-0">Click "New Equipment" to create one.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: CREATE EQUIPMENT ===== -->
<!-- ============================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle text-primary me-2"></i> New Equipment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="e.g. Hunter Hawkeye Elite" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Model</label>
                            <input type="text" class="form-control" name="model" placeholder="e.g. Hawkeye Elite">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Serial Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="serial_number" placeholder="e.g. TWA-JED-2023-003" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manufacturer</label>
                            <input type="text" class="form-control" name="manufacturer" placeholder="e.g. Hunter">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" class="form-control" name="location" placeholder="e.g. Jeddah - Tire Center">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="operational">Operational</option>
                                <option value="maintenance">Under Maintenance</option>
                                <option value="out_of_service">Out of Service</option>
                                <option value="retired">Retired</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Building</label>
                            <input type="text" class="form-control" name="building" placeholder="e.g. Building A">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floor</label>
                            <input type="text" class="form-control" name="floor" placeholder="e.g. 2nd Floor">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Installation Date</label>
                            <input type="date" class="form-control" name="installation_date">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warranty Expiry</label>
                            <input type="date" class="form-control" name="warranty_expiry">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Assigned Technician</label>
                            <select class="form-select" name="assigned_technician_id">
                                <option value="">Unassigned</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Additional details..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="createSubmit">
                        <i class="fas fa-spinner fa-spin d-none" id="createSpinner"></i>
                        <span id="createText">Create Equipment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: VIEW EQUIPMENT ===== -->
<!-- ============================================ -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-eye text-primary me-2"></i> Equipment Details
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
<!-- ===== MODAL: EDIT EQUIPMENT ===== -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-edit text-success me-2"></i> Edit Equipment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Model</label>
                            <input type="text" class="form-control" name="model" id="editModel">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Serial Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="serial_number" id="editSerial" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manufacturer</label>
                            <input type="text" class="form-control" name="manufacturer" id="editManufacturer">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" class="form-control" name="location" id="editLocation">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="editStatus" required>
                                <option value="operational">Operational</option>
                                <option value="maintenance">Under Maintenance</option>
                                <option value="out_of_service">Out of Service</option>
                                <option value="retired">Retired</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Building</label>
                            <input type="text" class="form-control" name="building" id="editBuilding">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floor</label>
                            <input type="text" class="form-control" name="floor" id="editFloor">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Installation Date</label>
                            <input type="date" class="form-control" name="installation_date" id="editInstallation">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warranty Expiry</label>
                            <input type="date" class="form-control" name="warranty_expiry" id="editWarranty">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Assigned Technician</label>
                            <select class="form-select" name="assigned_technician_id" id="editTechnician">
                                <option value="">Unassigned</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="editSubmit">
                        <i class="fas fa-spinner fa-spin d-none" id="editSpinner"></i>
                        <span id="editText">Update Equipment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: DELETE EQUIPMENT ===== -->
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
                <p class="mb-0">Are you sure you want to delete this equipment?</p>
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

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // ===== Toast =====
    function showToast(message, type = 'success') {
        const colors = { success: 'bg-success', error: 'bg-danger', warning: 'bg-warning', info: 'bg-info' };
        const toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) return;
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${colors[type] || 'bg-primary'} border-0`;
        toast.role = 'alert';
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

    // ===== ATTACH EVENT LISTENERS =====
    function attachEventListeners() {
        document.querySelectorAll('.status-item').forEach(el => {
            el.removeEventListener('click', handleStatusClick);
            el.addEventListener('click', handleStatusClick);
        });

        document.querySelectorAll('.view-btn').forEach(el => {
            el.removeEventListener('click', handleViewClick);
            el.addEventListener('click', handleViewClick);
        });

        document.querySelectorAll('.edit-btn').forEach(el => {
            el.removeEventListener('click', handleEditClick);
            el.addEventListener('click', handleEditClick);
        });

        document.querySelectorAll('.delete-btn').forEach(el => {
            el.removeEventListener('click', handleDeleteClick);
            el.addEventListener('click', handleDeleteClick);
        });

        const refreshBtn = document.getElementById('refreshTable');
        if (refreshBtn) {
            refreshBtn.removeEventListener('click', refreshTable);
            refreshBtn.addEventListener('click', refreshTable);
        }

        const clearBtn = document.getElementById('clearFilters');
        if (clearBtn) {
            clearBtn.removeEventListener('click', clearFilters);
            clearBtn.addEventListener('click', clearFilters);
        }

        ['searchInput', 'statusFilter', 'manufacturerFilter', 'locationFilter'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.removeEventListener('input', filterTable);
                el.removeEventListener('change', filterTable);
                el.addEventListener('input', filterTable);
                el.addEventListener('change', filterTable);
            }
        });
    }

    // ===== HANDLER: Status =====
    function handleStatusClick(e) {
        e.preventDefault();
        const item = e.currentTarget;
        const newStatus = item.dataset.status;
        const dropdown = item.closest('.dropdown');
        const id = dropdown.querySelector('.status-dropdown').dataset.id;
        const button = dropdown.querySelector('.status-dropdown');
        const badge = button.querySelector('.status-badge');

        const originalText = badge.textContent;
        badge.textContent = '⏳ ...';
        badge.style.opacity = '0.7';

        fetch(`/admin/equipment/toggle-status/${id}`, {
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
                badge.textContent = newStatus.replace('_', ' ').charAt(0).toUpperCase() + newStatus.replace('_', ' ').slice(1);
                button.dataset.currentStatus = newStatus;
                const bsDropdown = bootstrap.Dropdown.getInstance(button);
                if (bsDropdown) bsDropdown.hide();
                setTimeout(refreshTable, 500);
            } else {
                showToast(data.message || 'Update failed', 'error');
                badge.textContent = originalText;
                badge.style.opacity = '1';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating status', 'error');
            badge.textContent = originalText;
            badge.style.opacity = '1';
        });
    }

    // ===== HANDLER: View =====
    function handleViewClick(e) {
        const btn = e.currentTarget;
        const id = btn.dataset.id;
        const modal = document.getElementById('viewModal');
        const content = document.getElementById('viewContent');

        content.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>`;
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        fetch(`/admin/equipment/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.data;
                content.innerHTML = `
                    <div class="text-center mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb, #f5576c); color: white; font-size: 32px;">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h5 class="mt-2">${item.name}</h5>
                        <span class="status-badge status-${item.status}">${item.status.replace('_', ' ').toUpperCase()}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Model</small><div class="fw-semibold">${item.model || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Serial Number</small><div class="fw-semibold">${item.serial_number}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Manufacturer</small><div class="fw-semibold">${item.manufacturer || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Location</small><div class="fw-semibold">${item.location || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Building</small><div class="fw-semibold">${item.building || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Floor</small><div class="fw-semibold">${item.floor || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Installation Date</small><div class="fw-semibold">${item.installation_date ? new Date(item.installation_date).toLocaleDateString() : 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Warranty Expiry</small><div class="fw-semibold">${item.warranty_expiry ? new Date(item.warranty_expiry).toLocaleDateString() : 'N/A'}</div></div></div>
                        <div class="col-md-12"><div class="p-2 bg-light rounded"><small class="text-muted">Assigned Technician</small><div class="fw-semibold">${item.assigned_technician?.name || 'Unassigned'}</div></div></div>
                        ${item.description ? `<div class="col-12"><div class="p-2 bg-light rounded"><small class="text-muted">Description</small><div class="fw-semibold">${item.description}</div></div></div>` : ''}
                    </div>
                `;
            }
        })
        .catch(error => {
            content.innerHTML = `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle fa-2x"></i><p>Failed to load details</p></div>`;
        });
    }

    // ===== HANDLER: Edit =====
    let editModalInstance = null;

    function handleEditClick(e) {
        const btn = e.currentTarget;
        const id = btn.dataset.id;
        const modalElement = document.getElementById('editModal');

        const form = document.getElementById('editForm');
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        document.getElementById('editId').value = id;

        if (!editModalInstance) {
            editModalInstance = new bootstrap.Modal(modalElement);
        }
        editModalInstance.show();

        fetch(`/admin/equipment/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.data;
                document.getElementById('editName').value = item.name;
                document.getElementById('editModel').value = item.model || '';
                document.getElementById('editSerial').value = item.serial_number;
                document.getElementById('editManufacturer').value = item.manufacturer || '';
                document.getElementById('editLocation').value = item.location || '';
                document.getElementById('editStatus').value = item.status;
                document.getElementById('editBuilding').value = item.building || '';
                document.getElementById('editFloor').value = item.floor || '';
                document.getElementById('editInstallation').value = item.installation_date || '';
                document.getElementById('editWarranty').value = item.warranty_expiry || '';
                document.getElementById('editTechnician').value = item.assigned_technician_id || '';
                document.getElementById('editDescription').value = item.description || '';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ===== HANDLER: Delete =====
    let deleteModalInstance = null;

    function handleDeleteClick(e) {
        const btn = e.currentTarget;
        document.getElementById('deleteId').value = btn.dataset.id;
        const modalElement = document.getElementById('deleteModal');
        if (!deleteModalInstance) {
            deleteModalInstance = new bootstrap.Modal(modalElement);
        }
        deleteModalInstance.show();
    }

    // ===== REFRESH =====
    function refreshTable() {
        const container = document.getElementById('cardsContainer');
        if (!container) return;

        container.innerHTML = `
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        fetch('{{ route("admin.equipment.fetch") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const items = data.data;
                const totalCount = document.getElementById('totalCount');
                if (totalCount) totalCount.textContent = items.length;

                if (items.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-microchip fa-3x d-block mb-3"></i>
                            <h5>No Equipment Found</h5>
                            <p class="mb-0">Click "New Equipment" to create one.</p>
                        </div>
                    `;
                    attachEventListeners();
                    return;
                }

                let html = '';
                items.forEach((item) => {
                    const statusText = item.status.replace('_', ' ').charAt(0).toUpperCase() + item.status.replace('_', ' ').slice(1);

                    html += `
                        <div class="col-md-6 col-lg-4 equipment-card" data-id="${item.id}">
                            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color); transition: all 0.3s;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; font-weight: 700; font-size: 20px; flex-shrink: 0;">
                                                <i class="fas fa-microchip"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0" style="color: var(--text-primary);">${item.name.length > 25 ? item.name.substring(0, 25) + '...' : item.name}</h6>
                                                <small class="text-muted">${item.model || 'No model'}</small>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle status-dropdown"
                                                    data-id="${item.id}"
                                                    data-current-status="${item.status}"
                                                    style="border: none; padding: 0; background: transparent;"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <span class="status-badge status-${item.status}">
                                                    ${statusText}
                                                </span>
                                            </button>
                                            <ul class="dropdown-menu status-menu" data-id="${item.id}">
                                                <li><a class="dropdown-item status-item" data-status="operational" href="#">
                                                    <span class="badge-status operational">Operational</span>
                                                </a></li>
                                                <li><a class="dropdown-item status-item" data-status="maintenance" href="#">
                                                    <span class="badge-status maintenance">Under Maintenance</span>
                                                </a></li>
                                                <li><a class="dropdown-item status-item" data-status="out_of_service" href="#">
                                                    <span class="badge-status out_of_service">Out of Service</span>
                                                </a></li>
                                                <li><a class="dropdown-item status-item" data-status="retired" href="#">
                                                    <span class="badge-status retired">Retired</span>
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-barcode text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${item.serial_number}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-building text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${item.manufacturer || 'Unknown'}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-map-pin text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${item.location || 'No location'}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-user-cog text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${item.assigned_technician?.name || 'Unassigned'}</span>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary); font-size: 13px;">
                                                    ${item.installation_date ? new Date(item.installation_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—'}
                                                </div>
                                                <small class="text-muted" style="font-size: 10px;">Installed</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary); font-size: 13px;">
                                                    ${item.warranty_expiry ? new Date(item.warranty_expiry).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—'}
                                                </div>
                                                <small class="text-muted" style="font-size: 10px;">Warranty</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-end pt-2" style="border-top: 1px solid var(--border-color);">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary view-btn" data-id="${item.id}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success edit-btn" data-id="${item.id}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger delete-btn" data-id="${item.id}" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = html;
                attachEventListeners();
                filterTable();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to refresh data', 'error');
        });
    }

    // ===== FILTER =====
    function filterTable() {
        const search = document.getElementById('searchInput')?.value?.toLowerCase() || '';
        const status = document.getElementById('statusFilter')?.value || '';
        const manufacturer = document.getElementById('manufacturerFilter')?.value?.toLowerCase() || '';
        const location = document.getElementById('locationFilter')?.value?.toLowerCase() || '';

        const cards = document.querySelectorAll('.equipment-card');
        cards.forEach(card => {
            let show = true;
            const text = card.textContent.toLowerCase();

            if (search && !text.includes(search)) show = false;

            if (status && show) {
                const statusBtn = card.querySelector('.status-dropdown');
                if (statusBtn && statusBtn.dataset.currentStatus !== status) show = false;
            }

            if (manufacturer && show) {
                const manufacturerEl = card.querySelector('.fa-building')?.parentElement?.textContent?.toLowerCase();
                if (manufacturerEl && !manufacturerEl.includes(manufacturer)) show = false;
            }

            if (location && show) {
                const locationEl = card.querySelector('.fa-map-pin')?.parentElement?.textContent?.toLowerCase();
                if (locationEl && !locationEl.includes(location)) show = false;
            }

            card.style.display = show ? '' : 'none';
        });
    }

    // ===== CLEAR Filters =====
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('manufacturerFilter').value = '';
        document.getElementById('locationFilter').value = '';
        filterTable();
    }

    // ===== CREATE Form =====
    const createForm = document.getElementById('createForm');
    if (createForm) {
        const newCreateForm = createForm.cloneNode(true);
        createForm.parentNode.replaceChild(newCreateForm, createForm);

        newCreateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            const spinner = document.getElementById('createSpinner');
            const text = document.getElementById('createText');
            const submitBtn = document.getElementById('createSubmit');

            spinner.classList.remove('d-none');
            text.textContent = 'Creating...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch('{{ route("admin.equipment.store") }}', {
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
                    this.reset();
                } else {
                    if (data.errors) {
                        for (const [key, errors] of Object.entries(data.errors)) {
                            const input = this.querySelector(`[name="${key}"]`);
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
                spinner.classList.add('d-none');
                text.textContent = 'Create Equipment';
                submitBtn.disabled = false;
            });
        });
    }

    // ===== EDIT Form =====
    const editForm = document.getElementById('editForm');
    if (editForm) {
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);

        newEditForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editId').value;

            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            const spinner = document.getElementById('editSpinner');
            const text = document.getElementById('editText');
            const submitBtn = document.getElementById('editSubmit');

            spinner.classList.remove('d-none');
            text.textContent = 'Updating...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch(`/admin/equipment/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (editModalInstance) editModalInstance.hide();
                    showToast(data.message, 'success');
                    refreshTable();
                } else {
                    if (data.errors) {
                        for (const [key, errors] of Object.entries(data.errors)) {
                            const input = this.querySelector(`[name="${key}"]`);
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
                spinner.classList.add('d-none');
                text.textContent = 'Update Equipment';
                submitBtn.disabled = false;
            });
        });
    }

    // ===== DELETE =====
    const deleteConfirm = document.getElementById('deleteConfirm');
    if (deleteConfirm) {
        const newDeleteConfirm = deleteConfirm.cloneNode(true);
        deleteConfirm.parentNode.replaceChild(newDeleteConfirm, deleteConfirm);

        newDeleteConfirm.addEventListener('click', function() {
            const id = document.getElementById('deleteId').value;
            const spinner = document.getElementById('deleteSpinner');
            const text = document.getElementById('deleteText');

            spinner.classList.remove('d-none');
            text.textContent = 'Deleting...';
            this.disabled = true;

            fetch(`/admin/equipment/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (deleteModalInstance) deleteModalInstance.hide();
                    showToast(data.message, 'success');
                    refreshTable();
                } else {
                    showToast(data.message || 'Delete failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error deleting equipment', 'error');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                text.textContent = 'Delete';
                this.disabled = false;
            });
        });
    }

    // ===== CSS =====
    const style = document.createElement('style');
    style.textContent = `
        .status-badge {
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
        .status-badge:hover { opacity: 0.8; transform: scale(0.95); }
        .status-badge.status-operational { background: #d4edda; color: #155724; }
        .status-badge.status-maintenance { background: #fff3cd; color: #856404; }
        .status-badge.status-out_of_service { background: #f8d7da; color: #721c24; }
        .status-badge.status-retired { background: #e9ecef; color: #6c757d; }

        .badge-status.operational { background: #d4edda; color: #155724; }
        .badge-status.maintenance { background: #fff3cd; color: #856404; }
        .badge-status.out_of_service { background: #f8d7da; color: #721c24; }
        .badge-status.retired { background: #e9ecef; color: #6c757d; }

        .dropdown-toggle::after { display: none !important; }
        .dropdown-toggle { padding: 0 !important; background: transparent !important; border: none !important; }

        [data-bs-theme="dark"] .status-badge.status-operational { background: #1a3a2a; color: #66ff88; }
        [data-bs-theme="dark"] .status-badge.status-maintenance { background: #4a3a1a; color: #ffd700; }
        [data-bs-theme="dark"] .status-badge.status-out_of_service { background: #3a1a1a; color: #ff6666; }
        [data-bs-theme="dark"] .status-badge.status-retired { background: #2a2a2a; color: #aaa; }
        [data-bs-theme="dark"] .bg-light { background-color: var(--bg-body) !important; }
    `;
    document.head.appendChild(style);

    // ===== INIT =====
    attachEventListeners();

    console.log('🔧 Equipment Page Loaded Successfully!');
});
</script>
@endpush
