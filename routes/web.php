<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\WorkOrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Admin Dashboard Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Work Orders
    Route::get('/work-orders', [WorkOrderController::class, 'index'])->name('work-orders.index');
    Route::get('/work-orders/fetch', [WorkOrderController::class, 'fetch'])->name('work-orders.fetch');
    Route::get('/work-orders/{id}/view', [WorkOrderController::class, 'view'])->name('work-orders.view');
    Route::post('/work-orders', [WorkOrderController::class, 'store'])->name('work-orders.store');
    Route::post('/work-orders/toggle-status/{id}', [WorkOrderController::class, 'toggleStatus'])->name('work-orders.toggle-status');
    Route::post('/work-orders/toggle-priority/{id}', [WorkOrderController::class, 'togglePriority'])->name('work-orders.toggle-priority');
    Route::put('/work-orders/{id}', [WorkOrderController::class, 'update'])->name('work-orders.update');
    Route::delete('/work-orders/{id}', [WorkOrderController::class, 'destroy'])->name('work-orders.destroy');

    // Technicians
    Route::get('/technicians', [TechnicianController::class, 'index'])->name('technicians.index');
    Route::get('/technicians/fetch', [TechnicianController::class, 'fetch'])->name('technicians.fetch');
    Route::get('/technicians/{id}/view', [TechnicianController::class, 'view'])->name('technicians.view');
    Route::post('/technicians', [TechnicianController::class, 'store'])->name('technicians.store');
    Route::post('/technicians/toggle-status/{id}', [TechnicianController::class, 'toggleStatus'])->name('technicians.toggle-status');
    // Toggle Technician Active Status
    Route::post('/technicians/toggle-active/{id}', [TechnicianController::class, 'toggleActive'])
        ->name('technicians.toggle-active');
    Route::put('/technicians/{id}', [TechnicianController::class, 'update'])->name('technicians.update');
    Route::delete('/technicians/{id}', [TechnicianController::class, 'destroy'])->name('technicians.destroy');

    // Equipment
    Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/equipment/fetch', [EquipmentController::class, 'fetch'])->name('equipment.fetch');
    Route::get('/equipment/{id}/view', [EquipmentController::class, 'view'])->name('equipment.view');
    Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::post('/equipment/toggle-status/{id}', [EquipmentController::class, 'toggleStatus'])->name('equipment.toggle-status');
    Route::put('/equipment/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/fetch', [ClientController::class, 'fetch'])->name('clients.fetch');
    Route::get('/clients/{id}/view', [ClientController::class, 'view'])->name('clients.view');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::post('/clients/toggle-active/{id}', [ClientController::class, 'toggleActive'])->name('clients.toggle-active');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Spare Parts
    Route::get('/spare-parts', [SparePartController::class, 'index'])->name('spare-parts.index');
    Route::get('/spare-parts/fetch', [SparePartController::class, 'fetch'])->name('spare-parts.fetch');
    Route::get('/spare-parts/{id}/view', [SparePartController::class, 'view'])->name('spare-parts.view');
    Route::post('/spare-parts', [SparePartController::class, 'store'])->name('spare-parts.store');
    Route::put('/spare-parts/{id}', [SparePartController::class, 'update'])->name('spare-parts.update');
    Route::delete('/spare-parts/{id}', [SparePartController::class, 'destroy'])->name('spare-parts.destroy');

    });

require __DIR__ . '/auth.php';
