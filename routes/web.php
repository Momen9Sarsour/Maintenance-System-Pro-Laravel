<?php

use App\Http\Controllers\Admin\DashboardController;
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

    


});

require __DIR__ . '/auth.php';
