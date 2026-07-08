@extends('layouts.app')

@section('title', 'Spare Parts - MaintenancePro')
@section('page-title', 'Spare Parts')
@section('page-subtitle', 'Manage inventory of spare parts')

@section('content')

<!-- ===== FILTERS & SEARCH ===== -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Search</label>
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Name, SKU, or supplier...">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Category</label>
                <select class="form-select form-select-sm" id="categoryFilter">
                    <option value="">All Categories</option>
                    @php
                        $categories = ['Filters', 'Refrigerants', 'Seals', 'Oils', 'Belts', 'Bearings', 'Electrical', 'Other'];
                    @endphp
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Stock Status</label>
                <select class="form-select form-select-sm" id="stockFilter">
                    <option value="">All</option>
                    <option value="low">Low Stock</option>
                    <option value="ok">In Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Min Stock</label>
                <input type="number" class="form-control form-control-sm" id="minStockFilter" placeholder="e.g. 5">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Max Stock</label>
                <input type="number" class="form-control form-control-sm" id="maxStockFilter" placeholder="e.g. 50">
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
            <i class="fas fa-tools me-2 text-primary"></i>
            Spare Parts
            <span class="badge bg-primary ms-2" id="totalCount">{{ $spareParts->count() }}</span>
        </span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" id="refreshTable" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-outline-secondary" id="exportBtn">
                <i class="fas fa-file-export me-1"></i> Export
            </button>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> New Spare Part
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0" id="sparePartsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Min</th>
                        <th>Price</th>
                        <th>Supplier</th>
                        <th>Location</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($spareParts as $index => $part)
                    <tr data-id="{{ $part->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ Str::limit($part->name, 25) }}</div>
                            <small class="text-muted">{{ Str::limit($part->description ?? 'No description', 30) }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $part->sku }}</span></td>
                        <td>{{ $part->category ?? '—' }}</td>
                        <td>
                            @php
                                $stockClass = $part->stock_quantity <= $part->min_stock ? 'text-danger fw-bold' : ($part->stock_quantity == 0 ? 'text-danger' : 'text-success');
                            @endphp
                            <span class="{{ $stockClass }}">
                                {{ $part->stock_quantity }}
                                @if($part->stock_quantity <= $part->min_stock && $part->stock_quantity > 0)
                                    <i class="fas fa-exclamation-triangle text-warning ms-1" title="Low Stock"></i>
                                @elseif($part->stock_quantity == 0)
                                    <i class="fas fa-times-circle text-danger ms-1" title="Out of Stock"></i>
                                @else
                                    <i class="fas fa-check-circle text-success ms-1" title="In Stock"></i>
                                @endif
                            </span>
                        </td>
                        <td>{{ $part->min_stock }}</td>
                        <td>${{ number_format($part->price, 2) }}</td>
                        <td>{{ Str::limit($part->supplier ?? '—', 15) }}</td>
                        <td>
                            <div class="small">
                                {{ $part->warehouse ?? '' }}
                                @if($part->shelf)
                                    <br><span class="text-muted">Shelf: {{ $part->shelf }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary view-btn" data-id="{{ $part->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success edit-btn" data-id="{{ $part->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger delete-btn" data-id="{{ $part->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="fas fa-tools fa-3x d-block mb-3"></i>
                            <h5>No Spare Parts Found</h5>
                            <p class="mb-0">Click "New Spare Part" to create one.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: CREATE SPARE PART ===== -->
<!-- ============================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle text-primary me-2"></i> New Spare Part
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="e.g. HVAC Air Filter" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sku" placeholder="e.g. AF-HERV11-16" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" name="category">
                                <option value="">Select Category</option>
                                <option value="Filters">Filters</option>
                                <option value="Refrigerants">Refrigerants</option>
                                <option value="Seals">Seals</option>
                                <option value="Oils">Oils</option>
                                <option value="Belts">Belts</option>
                                <option value="Bearings">Bearings</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="price" placeholder="0.00" step="0.01" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="stock_quantity" placeholder="0" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Min Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="min_stock" placeholder="0" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Max Stock</label>
                            <input type="number" class="form-control" name="max_stock" placeholder="Optional" min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <input type="text" class="form-control" name="supplier" placeholder="e.g. FilterPro Supplies">
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Warehouse</label>
                            <input type="text" class="form-control" name="warehouse" placeholder="e.g. Warehouse A">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Shelf</label>
                            <input type="text" class="form-control" name="shelf" placeholder="e.g. Shelf 3">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" class="form-control" name="location" placeholder="e.g. Warehouse A - Shelf 3">
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
                        <span id="createText">Create Spare Part</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: VIEW SPARE PART ===== -->
<!-- ============================================ -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-eye text-primary me-2"></i> Spare Part Details
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
<!-- ===== MODAL: EDIT SPARE PART ===== -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-primary);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title">
                    <i class="fas fa-edit text-success me-2"></i> Edit Spare Part
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
                            <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sku" id="editSku" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" name="category" id="editCategory">
                                <option value="">Select Category</option>
                                <option value="Filters">Filters</option>
                                <option value="Refrigerants">Refrigerants</option>
                                <option value="Seals">Seals</option>
                                <option value="Oils">Oils</option>
                                <option value="Belts">Belts</option>
                                <option value="Bearings">Bearings</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="price" id="editPrice" step="0.01" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="stock_quantity" id="editStock" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Min Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="min_stock" id="editMinStock" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Max Stock</label>
                            <input type="number" class="form-control" name="max_stock" id="editMaxStock" min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <input type="text" class="form-control" name="supplier" id="editSupplier">
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Warehouse</label>
                            <input type="text" class="form-control" name="warehouse" id="editWarehouse">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Shelf</label>
                            <input type="text" class="form-control" name="shelf" id="editShelf">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" class="form-control" name="location" id="editLocation">
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
                        <span id="editText">Update Spare Part</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ===== MODAL: DELETE SPARE PART ===== -->
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
                <p class="mb-0">Are you sure you want to delete this spare part?</p>
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

        ['searchInput', 'categoryFilter', 'stockFilter', 'minStockFilter', 'maxStockFilter'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.removeEventListener('input', filterTable);
                el.removeEventListener('change', filterTable);
                el.addEventListener('input', filterTable);
                el.addEventListener('change', filterTable);
            }
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

        fetch(`/admin/spare-parts/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const part = data.data;
                content.innerHTML = `
                    <div class="text-center mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb, #f5576c); color: white; font-size: 32px;">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h5 class="mt-2">${part.name}</h5>
                        <span class="badge bg-secondary">${part.sku}</span>
                        ${part.category ? `<span class="badge bg-info ms-1">${part.category}</span>` : ''}
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><div class="p-2 bg-light rounded"><small class="text-muted">Stock</small><div class="fw-bold h5 ${part.stock_quantity <= part.min_stock ? 'text-danger' : 'text-success'}">${part.stock_quantity}</div></div></div>
                        <div class="col-md-4"><div class="p-2 bg-light rounded"><small class="text-muted">Min Stock</small><div class="fw-bold">${part.min_stock}</div></div></div>
                        <div class="col-md-4"><div class="p-2 bg-light rounded"><small class="text-muted">Max Stock</small><div class="fw-bold">${part.max_stock || '—'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Price</small><div class="fw-bold">$${Number(part.price).toFixed(2)}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Supplier</small><div class="fw-bold">${part.supplier || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Equipment</small><div class="fw-bold">${part.equipment?.name || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Location</small><div class="fw-bold">${part.location || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Warehouse</small><div class="fw-bold">${part.warehouse || 'N/A'}</div></div></div>
                        <div class="col-md-6"><div class="p-2 bg-light rounded"><small class="text-muted">Shelf</small><div class="fw-bold">${part.shelf || 'N/A'}</div></div></div>
                        ${part.description ? `<div class="col-12"><div class="p-2 bg-light rounded"><small class="text-muted">Description</small><div class="fw-bold">${part.description}</div></div></div>` : ''}
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

        fetch(`/admin/spare-parts/${id}/view`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const part = data.data;
                document.getElementById('editName').value = part.name;
                document.getElementById('editSku').value = part.sku;
                document.getElementById('editCategory').value = part.category || '';
                document.getElementById('editPrice').value = part.price;
                document.getElementById('editStock').value = part.stock_quantity;
                document.getElementById('editMinStock').value = part.min_stock;
                document.getElementById('editMaxStock').value = part.max_stock || '';
                document.getElementById('editSupplier').value = part.supplier || '';
                document.getElementById('editEquipment').value = part.equipment_id || '';
                document.getElementById('editWarehouse').value = part.warehouse || '';
                document.getElementById('editShelf').value = part.shelf || '';
                document.getElementById('editLocation').value = part.location || '';
                document.getElementById('editDescription').value = part.description || '';
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
        const tbody = document.getElementById('tableBody');
        if (!tbody) return;

        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </td>
            </tr>
        `;

        fetch('{{ route("admin.spare-parts.fetch") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const parts = data.data;
                const totalCount = document.getElementById('totalCount');
                if (totalCount) totalCount.textContent = parts.length;

                if (parts.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-tools fa-3x d-block mb-3"></i>
                                <h5>No Spare Parts Found</h5>
                            </td>
                        </tr>
                    `;
                    attachEventListeners();
                    return;
                }

                let html = '';
                parts.forEach((part, index) => {
                    const stockClass = part.stock_quantity <= part.min_stock ? 'text-danger fw-bold' : (part.stock_quantity == 0 ? 'text-danger' : 'text-success');

                    html += `
                        <tr data-id="${part.id}">
                            <td>${index + 1}</td>
                            <td>
                                <div class="fw-semibold">${part.name.length > 25 ? part.name.substring(0, 25) + '...' : part.name}</div>
                                <small class="text-muted">${part.description ? (part.description.length > 30 ? part.description.substring(0, 30) + '...' : part.description) : 'No description'}</small>
                            </td>
                            <td><span class="badge bg-light text-dark">${part.sku}</span></td>
                            <td>${part.category || '—'}</td>
                            <td>
                                <span class="${stockClass}">
                                    ${part.stock_quantity}
                                    ${part.stock_quantity <= part.min_stock && part.stock_quantity > 0 ? '<i class="fas fa-exclamation-triangle text-warning ms-1" title="Low Stock"></i>' : ''}
                                    ${part.stock_quantity == 0 ? '<i class="fas fa-times-circle text-danger ms-1" title="Out of Stock"></i>' : ''}
                                    ${part.stock_quantity > part.min_stock ? '<i class="fas fa-check-circle text-success ms-1" title="In Stock"></i>' : ''}
                                </span>
                            </td>
                            <td>${part.min_stock}</td>
                            <td>$${Number(part.price).toFixed(2)}</td>
                            <td>${part.supplier ? (part.supplier.length > 15 ? part.supplier.substring(0, 15) + '...' : part.supplier) : '—'}</td>
                            <td>
                                <div class="small">
                                    ${part.warehouse || ''}
                                    ${part.shelf ? `<br><span class="text-muted">Shelf: ${part.shelf}</span>` : ''}
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary view-btn" data-id="${part.id}" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success edit-btn" data-id="${part.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger delete-btn" data-id="${part.id}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tbody.innerHTML = html;
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
        const category = document.getElementById('categoryFilter')?.value || '';
        const stock = document.getElementById('stockFilter')?.value || '';
        const minStock = parseInt(document.getElementById('minStockFilter')?.value) || null;
        const maxStock = parseInt(document.getElementById('maxStockFilter')?.value) || null;

        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            if (row.querySelector('.text-center')) return;

            const cells = row.querySelectorAll('td');
            if (cells.length < 10) return;

            let show = true;
            const text = row.textContent.toLowerCase();

            if (search && !text.includes(search)) show = false;

            if (category && show) {
                const categoryCell = cells[3]?.textContent.trim();
                if (categoryCell !== category) show = false;
            }

            if (stock && show) {
                const stockText = cells[4]?.textContent.trim();
                const stockNum = parseInt(stockText) || 0;
                const minStockNum = parseInt(cells[5]?.textContent.trim()) || 0;

                if (stock === 'low' && stockNum > minStockNum) show = false;
                if (stock === 'out' && stockNum > 0) show = false;
                if (stock === 'ok' && stockNum <= minStockNum) show = false;
            }

            if (minStock !== null && show) {
                const stockNum = parseInt(cells[4]?.textContent.trim()) || 0;
                if (stockNum < minStock) show = false;
            }

            if (maxStock !== null && show) {
                const stockNum = parseInt(cells[4]?.textContent.trim()) || 0;
                if (stockNum > maxStock) show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    // ===== CLEAR Filters =====
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('stockFilter').value = '';
        document.getElementById('minStockFilter').value = '';
        document.getElementById('maxStockFilter').value = '';
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

            fetch('{{ route("admin.spare-parts.store") }}', {
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
                text.textContent = 'Create Spare Part';
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

            fetch(`/admin/spare-parts/${id}`, {
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
                text.textContent = 'Update Spare Part';
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

            fetch(`/admin/spare-parts/${id}`, {
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
                showToast('Error deleting spare part', 'error');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                text.textContent = 'Delete';
                this.disabled = false;
            });
        });
    }

    // ===== Export =====
    document.getElementById('exportBtn')?.addEventListener('click', function() {
        showToast('Export functionality coming soon!', 'info');
    });

    // ===== CSS =====
    const style = document.createElement('style');
    style.textContent = `
        [data-bs-theme="dark"] .bg-light { background-color: var(--bg-body) !important; }
        [data-bs-theme="dark"] .badge.bg-light { background-color: #2a2a2a !important; color: #e6edf3 !important; }
    `;
    document.head.appendChild(style);

    // ===== INIT =====
    attachEventListeners();

    console.log('🔧 Spare Parts Page Loaded Successfully!');
});
</script>
@endpush
