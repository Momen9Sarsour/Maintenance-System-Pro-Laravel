@extends('layouts.app')

@section('title', 'Technicians - MaintenancePro')
@section('page-title', 'Technicians')
@section('page-subtitle', 'Manage all maintenance technicians')

@section('content')

<!-- ===== FILTERS & SEARCH ===== -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Search</label>
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Name, email, or specialization...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="busy">Busy</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Active</label>
                <select class="form-select form-select-sm" id="activeFilter">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-secondary w-100" id="clearFilters" title="Clear Filters">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== TECHNICIAN CARDS ===== -->
<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>
            <i class="fas fa-user-cog me-2 text-primary"></i>
            Technicians
            <span class="badge bg-primary ms-2" id="totalCount">{{ $technicians->count() }}</span>
        </span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" id="refreshTable" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> New Technician
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4" id="cardsContainer">
            @forelse($technicians as $tech)
            <div class="col-md-6 col-lg-4 technician-card" data-id="{{ $tech->id }}">
                <div class="card h-100 border-0 shadow-sm rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color); transition: all 0.3s;">
                    <div class="card-body p-4">
                        <!-- Header with Avatar & Name -->
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 700; font-size: 22px; flex-shrink: 0;">
                                    {{ strtoupper(substr($tech->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);">{{ $tech->name }}</h6>
                                    <small class="text-muted">{{ $tech->technicianProfile->specialization ?? 'General Maintenance' }}</small>
                                </div>
                            </div>
                            <!-- Status Badge -->
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle status-dropdown"
                                        data-id="{{ $tech->id }}"
                                        data-current-status="{{ $tech->technicianProfile->status ?? 'offline' }}"
                                        style="border: none; padding: 0; background: transparent;"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <span class="status-badge status-{{ $tech->technicianProfile->status ?? 'offline' }}">
                                        {{ ucfirst($tech->technicianProfile->status ?? 'Offline') }}
                                    </span>
                                </button>
                                <ul class="dropdown-menu status-menu" data-id="{{ $tech->id }}">
                                    <li><a class="dropdown-item status-item" data-status="available" href="#">
                                        <span class="badge-status available">Available</span>
                                    </a></li>
                                    <li><a class="dropdown-item status-item" data-status="busy" href="#">
                                        <span class="badge-status busy">Busy</span>
                                    </a></li>
                                    <li><a class="dropdown-item status-item" data-status="offline" href="#">
                                        <span class="badge-status offline">Offline</span>
                                    </a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-envelope text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $tech->email }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-phone text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $tech->phone ?? 'No phone' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-map-marker-alt text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">
                                    {{ $tech->technicianProfile->latitude ?? '—' }}, {{ $tech->technicianProfile->longitude ?? '—' }}
                                </span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary);">{{ $tech->technicianProfile->tasks_completed ?? 0 }}</div>
                                    <small class="text-muted" style="font-size: 10px;">Tasks</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary);">{{ $tech->technicianProfile->rating ?? 0 }}</div>
                                    <small class="text-muted" style="font-size: 10px;">Rating</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary);">{{ $tech->technicianProfile->first_time_fix_rate ?? 0 }}%</div>
                                    <small class="text-muted" style="font-size: 10px;">Fix Rate</small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions & Active Toggle -->
                        <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid var(--border-color);">
                            <!-- Active Toggle -->
                            <button type="button" class="toggle-active-btn" data-id="{{ $tech->id }}"
                                    style="background:none;border:none;cursor:pointer;padding:0;">
                                @if($tech->is_active)
                                    <span class="active-badge active" style="background:#d1fae5;color:#065f46;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                        ● Active
                                    </span>
                                @else
                                    <span class="active-badge inactive" style="background:#fee2e2;color:#991b1b;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                        ● Inactive
                                    </span>
                                @endif
                            </button>

                            <!-- Action Buttons -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary view-btn" data-id="{{ $tech->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success edit-btn" data-id="{{ $tech->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger delete-btn" data-id="{{ $tech->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-users fa-3x d-block mb-3"></i>
                <h5>No Technicians Found</h5>
                <p class="mb-0">Click "New Technician" to create one.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: CREATE TECHNICIAN ===== -->
<!-- ============================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus text-primary me-2"></i> Create New Technician
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="e.g. Amor Ghamdi" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="tech@example.com" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+966 50 000 0000">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Specialization</label>
                            <input type="text" class="form-control" name="specialization" placeholder="e.g. Engine and transmission maintenance">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="available">Available</option>
                                <option value="busy">Busy</option>
                                <option value="offline">Offline</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Min. 8 characters" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="createSubmit">
                        <i class="fas fa-spinner fa-spin d-none" id="createSpinner"></i>
                        <span id="createText">Create Technician</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: VIEW TECHNICIAN ===== -->
<!-- ============================================ -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle text-primary me-2"></i> Technician Details
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
<!-- ===== MODAL: EDIT TECHNICIAN ===== -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit text-success me-2"></i> Edit Technician
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
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" name="phone" id="editPhone">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Specialization</label>
                            <input type="text" class="form-control" name="specialization" id="editSpecialization">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="editStatus" required>
                                <option value="available">Available</option>
                                <option value="busy">Busy</option>
                                <option value="offline">Offline</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Active <span class="text-danger">*</span></label>
                            <select class="form-select" name="is_active" id="editActive" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="editSubmit">
                        <i class="fas fa-spinner fa-spin d-none" id="editSpinner"></i>
                        <span id="editText">Update Technician</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: DELETE TECHNICIAN ===== -->
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
                <p class="mb-0">Are you sure you want to delete this technician?</p>
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

        document.querySelectorAll('.toggle-active-btn').forEach(el => {
            el.removeEventListener('click', handleToggleActive);
            el.addEventListener('click', handleToggleActive);
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

        ['searchInput', 'statusFilter', 'activeFilter'].forEach(id => {
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

        fetch(`/admin/technicians/toggle-status/${id}`, {
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
                badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
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

    // ===== HANDLER: Toggle Active =====
    function handleToggleActive(e) {
        e.preventDefault();
        const btn = e.currentTarget;
        const id = btn.dataset.id;
        const badge = btn.querySelector('.active-badge');

        const originalText = badge.textContent;
        const originalBg = badge.style.background;
        const originalColor = badge.style.color;

        badge.textContent = '⏳ ...';
        badge.style.opacity = '0.7';

        fetch(`/admin/technicians/toggle-active/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_active) {
                    badge.className = 'active-badge active';
                    badge.style.background = '#d1fae5';
                    badge.style.color = '#065f46';
                    badge.textContent = '● Active';
                } else {
                    badge.className = 'active-badge inactive';
                    badge.style.background = '#fee2e2';
                    badge.style.color = '#991b1b';
                    badge.textContent = '● Inactive';
                }
                badge.style.opacity = '1';
                showToast(data.message, 'success');
                setTimeout(refreshTable, 500);
            } else {
                showToast(data.message || 'Update failed', 'error');
                badge.textContent = originalText;
                badge.style.background = originalBg;
                badge.style.color = originalColor;
                badge.style.opacity = '1';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating active status', 'error');
            badge.textContent = originalText;
            badge.style.background = originalBg;
            badge.style.color = originalColor;
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

        fetch(`/admin/technicians/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tech = data.data;
                const profile = tech.technician_profile || {};
                content.innerHTML = `
                    <div class="text-center mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; font-size: 32px; font-weight: 700;">
                            ${tech.name.charAt(0).toUpperCase()}
                        </div>
                        <h5 class="mt-2">${tech.name}</h5>
                        <span class="badge ${tech.is_active ? 'bg-success' : 'bg-danger'}">${tech.is_active ? 'Active' : 'Inactive'}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Email</small><div class="fw-semibold">${tech.email}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Phone</small><div class="fw-semibold">${tech.phone || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Specialization</small><div class="fw-semibold">${profile.specialization || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Status</small><div><span class="badge-status ${profile.status || 'offline'}">${(profile.status || 'Offline').toUpperCase()}</span></div></div></div>
                        <div class="col-md-4"><div class="p-2 bg-light rounded text-center"><small class="text-muted">Tasks</small><div class="fw-bold h4">${profile.tasks_completed || 0}</div></div></div>
                        <div class="col-md-4"><div class="p-2 bg-light rounded text-center"><small class="text-muted">Rating</small><div class="fw-bold h4">${profile.rating || 0} /5</div></div></div>
                        <div class="col-md-4"><div class="p-2 bg-light rounded text-center"><small class="text-muted">Fix Rate</small><div class="fw-bold h4">${profile.first_time_fix_rate || 0}%</div></div></div>
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

        fetch(`/admin/technicians/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tech = data.data;
                const profile = tech.technician_profile || {};
                document.getElementById('editName').value = tech.name;
                document.getElementById('editEmail').value = tech.email;
                document.getElementById('editPhone').value = tech.phone || '';
                document.getElementById('editSpecialization').value = profile.specialization || '';
                document.getElementById('editStatus').value = profile.status || 'offline';
                document.getElementById('editActive').value = tech.is_active ? '1' : '0';
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

    // ===== REFRESH Table =====
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

        fetch('{{ route("admin.technicians.fetch") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const technicians = data.data;
                const totalCount = document.getElementById('totalCount');
                if (totalCount) totalCount.textContent = technicians.length;

                if (technicians.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x d-block mb-3"></i>
                            <h5>No Technicians Found</h5>
                            <p class="mb-0">Click "New Technician" to create one.</p>
                        </div>
                    `;
                    attachEventListeners();
                    return;
                }

                let html = '';
                technicians.forEach((tech) => {
                    const profile = tech.technician_profile || {};
                    const statusClass = profile.status || 'offline';
                    const statusText = (profile.status || 'offline').charAt(0).toUpperCase() + (profile.status || 'offline').slice(1);

                    html += `
                        <div class="col-md-6 col-lg-4 technician-card" data-id="${tech.id}">
                            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color); transition: all 0.3s;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 700; font-size: 22px; flex-shrink: 0;">
                                                ${tech.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0" style="color: var(--text-primary);">${tech.name}</h6>
                                                <small class="text-muted">${profile.specialization || 'General Maintenance'}</small>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle status-dropdown"
                                                    data-id="${tech.id}"
                                                    data-current-status="${statusClass}"
                                                    style="border: none; padding: 0; background: transparent;"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <span class="status-badge status-${statusClass}">
                                                    ${statusText}
                                                </span>
                                            </button>
                                            <ul class="dropdown-menu status-menu" data-id="${tech.id}">
                                                <li><a class="dropdown-item status-item" data-status="available" href="#">
                                                    <span class="badge-status available">Available</span>
                                                </a></li>
                                                <li><a class="dropdown-item status-item" data-status="busy" href="#">
                                                    <span class="badge-status busy">Busy</span>
                                                </a></li>
                                                <li><a class="dropdown-item status-item" data-status="offline" href="#">
                                                    <span class="badge-status offline">Offline</span>
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-envelope text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${tech.email}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-phone text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${tech.phone || 'No phone'}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${profile.latitude || '—'}, ${profile.longitude || '—'}</span>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary);">${profile.tasks_completed || 0}</div>
                                                <small class="text-muted" style="font-size: 10px;">Tasks</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary);">${profile.rating || 0}</div>
                                                <small class="text-muted" style="font-size: 10px;">Rating</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary);">${profile.first_time_fix_rate || 0}%</div>
                                                <small class="text-muted" style="font-size: 10px;">Fix Rate</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid var(--border-color);">
                                        <button type="button" class="toggle-active-btn" data-id="${tech.id}"
                                                style="background:none;border:none;cursor:pointer;padding:0;">
                                            ${tech.is_active ? `
                                                <span class="active-badge active" style="background:#d1fae5;color:#065f46;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                                    ● Active
                                                </span>
                                            ` : `
                                                <span class="active-badge inactive" style="background:#fee2e2;color:#991b1b;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                                    ● Inactive
                                                </span>
                                            `}
                                        </button>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary view-btn" data-id="${tech.id}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success edit-btn" data-id="${tech.id}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger delete-btn" data-id="${tech.id}" title="Delete">
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
        const active = document.getElementById('activeFilter')?.value || '';

        const cards = document.querySelectorAll('.technician-card');
        cards.forEach(card => {
            let show = true;
            const text = card.textContent.toLowerCase();

            if (search && !text.includes(search)) show = false;

            if (status && show) {
                const statusBtn = card.querySelector('.status-dropdown');
                if (statusBtn && statusBtn.dataset.currentStatus !== status) show = false;
            }

            if (active && show) {
                const activeBtn = card.querySelector('.toggle-active-btn');
                if (activeBtn) {
                    const badge = activeBtn.querySelector('.active-badge');
                    const isActive = badge?.classList.contains('active');
                    if (active === '1' && !isActive) show = false;
                    if (active === '0' && isActive) show = false;
                }
            }

            card.style.display = show ? '' : 'none';
        });
    }

    // ===== CLEAR Filters =====
    function clearFilters() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const activeFilter = document.getElementById('activeFilter');

        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (activeFilter) activeFilter.value = '';
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

            fetch('{{ route("admin.technicians.store") }}', {
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
                text.textContent = 'Create Technician';
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

            fetch(`/admin/technicians/${id}`, {
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
                text.textContent = 'Update Technician';
                submitBtn.disabled = false;
            });
        });
    }

    // ===== DELETE Confirm =====
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

            fetch(`/admin/technicians/${id}`, {
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
                showToast('Error deleting technician', 'error');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                text.textContent = 'Delete';
                this.disabled = false;
            });
        });
    }

    // ===== INIT =====
    attachEventListeners();

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
        .status-badge.status-available { background: #d4edda; color: #155724; }
        .status-badge.status-busy { background: #fff3cd; color: #856404; }
        .status-badge.status-offline { background: #f8d7da; color: #721c24; }
        .badge-status.available { background: #d4edda; color: #155724; }
        .badge-status.busy { background: #fff3cd; color: #856404; }
        .badge-status.offline { background: #f8d7da; color: #721c24; }
        .dropdown-toggle::after { display: none !important; }
        .dropdown-toggle { padding: 0 !important; background: transparent !important; border: none !important; }

        [data-bs-theme="dark"] .status-badge.status-available { background: #1a3a2a; color: #66ff88; }
        [data-bs-theme="dark"] .status-badge.status-busy { background: #4a3a1a; color: #ffd700; }
        [data-bs-theme="dark"] .status-badge.status-offline { background: #3a1a1a; color: #ff6666; }
        [data-bs-theme="dark"] .badge-status.available { background: #1a3a2a; color: #66ff88; }
        [data-bs-theme="dark"] .badge-status.busy { background: #4a3a1a; color: #ffd700; }
        [data-bs-theme="dark"] .badge-status.offline { background: #3a1a1a; color: #ff6666; }
        [data-bs-theme="dark"] .bg-light { background-color: var(--bg-body) !important; }
    `;
    document.head.appendChild(style);

    console.log('👨‍🔧 Technicians Page Loaded Successfully!');
});
</script>
@endpush
