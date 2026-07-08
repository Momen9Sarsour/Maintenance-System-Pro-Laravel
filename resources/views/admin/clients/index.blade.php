@extends('layouts.app')

@section('title', 'Clients - MaintenancePro')
@section('page-title', 'Clients')
@section('page-subtitle', 'Manage all client accounts')

@section('content')

<!-- ===== FILTERS & SEARCH ===== -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Search</label>
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Name, email, or company...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Active</label>
                <select class="form-select form-select-sm" id="activeFilter">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Has Requests</label>
                <select class="form-select form-select-sm" id="requestsFilter">
                    <option value="">All</option>
                    <option value="1">Has Requests</option>
                    <option value="0">No Requests</option>
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

<!-- ===== CLIENTS CARDS ===== -->
<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>
            <i class="fas fa-users me-2 text-primary"></i>
            Clients
            <span class="badge bg-primary ms-2" id="totalCount">{{ $clients->count() }}</span>
        </span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" id="refreshTable" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> New Client
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4" id="cardsContainer">
            @forelse($clients as $client)
            <div class="col-md-6 col-lg-4 client-card" data-id="{{ $client->id }}">
                <div class="card h-100 border-0 shadow-sm rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color); transition: all 0.3s;">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; font-weight: 700; font-size: 22px; flex-shrink: 0;">
                                    {{ strtoupper(substr($client->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);">{{ Str::limit($client->name, 20) }}</h6>
                                    <small class="text-muted">{{ $client->company_name ?? 'Individual' }}</small>
                                </div>
                            </div>
                            <!-- Active Toggle -->
                            <button type="button" class="toggle-active-btn" data-id="{{ $client->id }}"
                                    style="background:none;border:none;cursor:pointer;padding:0;">
                                @if($client->is_active)
                                    <span class="active-badge active" style="background:#d1fae5;color:#065f46;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                        ● Active
                                    </span>
                                @else
                                    <span class="active-badge inactive" style="background:#fee2e2;color:#991b1b;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                        ● Inactive
                                    </span>
                                @endif
                            </button>
                        </div>

                        <!-- Contact Info -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-envelope text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $client->email }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-phone text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $client->phone ?? 'No phone' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-building text-muted" style="width: 18px; font-size: 13px;"></i>
                                <span style="color: var(--text-secondary); font-size: 14px;">{{ $client->company_name ?? 'Individual' }}</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary);">{{ $client->client_work_orders_count ?? 0 }}</div>
                                    <small class="text-muted" style="font-size: 10px;">Requests</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                    <div class="fw-bold" style="color: var(--text-primary);">{{ $client->invoices_count ?? 0 }}</div>
                                    <small class="text-muted" style="font-size: 10px;">Invoices</small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex align-items-center justify-content-end pt-2" style="border-top: 1px solid var(--border-color);">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary view-btn" data-id="{{ $client->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success edit-btn" data-id="{{ $client->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger delete-btn" data-id="{{ $client->id }}" title="Delete">
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
                <h5>No Clients Found</h5>
                <p class="mb-0">Click "New Client" to create one.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: CREATE CLIENT ===== -->
<!-- ============================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus text-primary me-2"></i> Create New Client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="e.g. John Smith" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="john@example.com" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+966 50 000 0000">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Company Name</label>
                            <input type="text" class="form-control" name="company_name" placeholder="e.g. Acme Corp">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea class="form-control" name="address" rows="2" placeholder="Full address..."></textarea>
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
                        <span id="createText">Create Client</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: VIEW CLIENT ===== -->
<!-- ============================================ -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle text-primary me-2"></i> Client Details
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
<!-- ===== MODAL: EDIT CLIENT ===== -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit text-success me-2"></i> Edit Client
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
                            <label class="form-label fw-semibold">Company Name</label>
                            <input type="text" class="form-control" name="company_name" id="editCompany">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea class="form-control" name="address" id="editAddress" rows="2"></textarea>
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
                        <span id="editText">Update Client</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: DELETE CLIENT ===== -->
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
                <p class="mb-0">Are you sure you want to delete this client?</p>
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

        ['searchInput', 'activeFilter', 'requestsFilter'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.removeEventListener('input', filterTable);
                el.removeEventListener('change', filterTable);
                el.addEventListener('input', filterTable);
                el.addEventListener('change', filterTable);
            }
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

        fetch(`/admin/clients/toggle-active/${id}`, {
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

        fetch(`/admin/clients/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const client = data.data;
                const recentOrders = client.client_work_orders || [];

                let ordersHtml = '';
                if (recentOrders.length > 0) {
                    ordersHtml = '<div class="mt-3"><small class="text-muted">Recent Requests:</small><ul class="list-unstyled mt-1">';
                    recentOrders.forEach(order => {
                        ordersHtml += `
                            <li class="d-flex justify-content-between align-items-center p-2 mb-1 rounded" style="background: var(--bg-body);">
                                <span class="small">${order.title}</span>
                                <span class="badge-status ${order.status}">${order.status.replace('_', ' ')}</span>
                            </li>
                        `;
                    });
                    ordersHtml += '</ul></div>';
                }

                content.innerHTML = `
                    <div class="text-center mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; font-size: 32px; font-weight: 700;">
                            ${client.name.charAt(0).toUpperCase()}
                        </div>
                        <h5 class="mt-2">${client.name}</h5>
                        <span class="badge ${client.is_active ? 'bg-success' : 'bg-danger'}">${client.is_active ? 'Active' : 'Inactive'}</span>
                        ${client.company_name ? `<span class="badge bg-primary ms-1">${client.company_name}</span>` : ''}
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Email</small><div class="fw-semibold">${client.email}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Phone</small><div class="fw-semibold">${client.phone || 'N/A'}</div></div></div>
                        <div class="col-md-12"><div class="p-2 bg-light rounded"><small class="text-muted">Address</small><div class="fw-semibold">${client.address || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded text-center"><small class="text-muted">Total Requests</small><div class="fw-bold h4">${client.client_work_orders_count || 0}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded text-center"><small class="text-muted">Total Invoices</small><div class="fw-bold h4">${client.invoices_count || 0}</div></div></div>
                        ${ordersHtml}
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

        fetch(`/admin/clients/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const client = data.data;
                document.getElementById('editName').value = client.name;
                document.getElementById('editEmail').value = client.email;
                document.getElementById('editPhone').value = client.phone || '';
                document.getElementById('editCompany').value = client.company_name || '';
                document.getElementById('editAddress').value = client.address || '';
                document.getElementById('editActive').value = client.is_active ? '1' : '0';
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

        fetch('{{ route("admin.clients.fetch") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const clients = data.data;
                const totalCount = document.getElementById('totalCount');
                if (totalCount) totalCount.textContent = clients.length;

                if (clients.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x d-block mb-3"></i>
                            <h5>No Clients Found</h5>
                            <p class="mb-0">Click "New Client" to create one.</p>
                        </div>
                    `;
                    attachEventListeners();
                    return;
                }

                let html = '';
                clients.forEach((client) => {
                    html += `
                        <div class="col-md-6 col-lg-4 client-card" data-id="${client.id}">
                            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color); transition: all 0.3s;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; font-weight: 700; font-size: 22px; flex-shrink: 0;">
                                                ${client.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0" style="color: var(--text-primary);">${client.name.length > 20 ? client.name.substring(0, 20) + '...' : client.name}</h6>
                                                <small class="text-muted">${client.company_name || 'Individual'}</small>
                                            </div>
                                        </div>
                                        <button type="button" class="toggle-active-btn" data-id="${client.id}"
                                                style="background:none;border:none;cursor:pointer;padding:0;">
                                            ${client.is_active ? `
                                                <span class="active-badge active" style="background:#d1fae5;color:#065f46;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                                    ● Active
                                                </span>
                                            ` : `
                                                <span class="active-badge inactive" style="background:#fee2e2;color:#991b1b;padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;transition:all 0.2s;">
                                                    ● Inactive
                                                </span>
                                            `}
                                        </button>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-envelope text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${client.email}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-phone text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${client.phone || 'No phone'}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-building text-muted" style="width: 18px; font-size: 13px;"></i>
                                            <span style="color: var(--text-secondary); font-size: 14px;">${client.company_name || 'Individual'}</span>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary);">${client.client_work_orders_count || 0}</div>
                                                <small class="text-muted" style="font-size: 10px;">Requests</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 rounded-3" style="background: var(--bg-body);">
                                                <div class="fw-bold" style="color: var(--text-primary);">${client.invoices_count || 0}</div>
                                                <small class="text-muted" style="font-size: 10px;">Invoices</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-end pt-2" style="border-top: 1px solid var(--border-color);">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary view-btn" data-id="${client.id}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success edit-btn" data-id="${client.id}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger delete-btn" data-id="${client.id}" title="Delete">
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
        const active = document.getElementById('activeFilter')?.value || '';
        const hasRequests = document.getElementById('requestsFilter')?.value || '';

        const cards = document.querySelectorAll('.client-card');
        cards.forEach(card => {
            let show = true;
            const text = card.textContent.toLowerCase();

            if (search && !text.includes(search)) show = false;

            if (active && show) {
                const activeBtn = card.querySelector('.toggle-active-btn');
                if (activeBtn) {
                    const badge = activeBtn.querySelector('.active-badge');
                    const isActive = badge?.classList.contains('active');
                    if (active === '1' && !isActive) show = false;
                    if (active === '0' && isActive) show = false;
                }
            }

            if (hasRequests && show) {
                const requestsCount = card.querySelector('.col-6:first-child .fw-bold')?.textContent || '0';
                if (hasRequests === '1' && parseInt(requestsCount) === 0) show = false;
                if (hasRequests === '0' && parseInt(requestsCount) > 0) show = false;
            }

            card.style.display = show ? '' : 'none';
        });
    }

    // ===== CLEAR Filters =====
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('activeFilter').value = '';
        document.getElementById('requestsFilter').value = '';
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

            fetch('{{ route("admin.clients.store") }}', {
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
                text.textContent = 'Create Client';
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

            fetch(`/admin/clients/${id}`, {
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
                text.textContent = 'Update Client';
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

            fetch(`/admin/clients/${id}`, {
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
                showToast('Error deleting client', 'error');
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
        .active-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            transition: all 0.2s;
            display: inline-block;
        }
        .active-badge:hover { opacity: 0.8; transform: scale(0.95); }
        .active-badge.active { background: #d1fae5; color: #065f46; }
        .active-badge.inactive { background: #fee2e2; color: #991b1b; }

        .badge-status {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-status.pending { background: #fff3cd; color: #856404; }
        .badge-status.in_progress { background: #cce5ff; color: #004085; }
        .badge-status.completed { background: #d4edda; color: #155724; }
        .badge-status.cancelled { background: #f8d7da; color: #721c24; }

        [data-bs-theme="dark"] .bg-light { background-color: var(--bg-body) !important; }
        [data-bs-theme="dark"] .active-badge.active { background: #1a3a2a; color: #66ff88; }
        [data-bs-theme="dark"] .active-badge.inactive { background: #3a1a1a; color: #ff6666; }
        [data-bs-theme="dark"] .badge-status.pending { background: #4a3a1a; color: #ffd700; }
        [data-bs-theme="dark"] .badge-status.in_progress { background: #1a2a4a; color: #66b0ff; }
        [data-bs-theme="dark"] .badge-status.completed { background: #1a3a2a; color: #66ff88; }
        [data-bs-theme="dark"] .badge-status.cancelled { background: #3a1a1a; color: #ff6666; }
    `;
    document.head.appendChild(style);

    // ===== INIT =====
    attachEventListeners();

    console.log('👤 Clients Page Loaded Successfully!');
});
</script>
@endpush
