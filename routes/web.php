<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AllocateController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

Route::get('/debug-vite', function () {
    $manifestPath = config('vite.manifest_path');
    
    return response()->json([
        'manifest_path' => $manifestPath,
        'file_exists' => file_exists($manifestPath),
        'real_path' => realpath($manifestPath),
    ]);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/allocates/{allocateHardware}/show', [AllocateController::class, 'show'])->name('allocates.hardware.show');

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Locations
    Route::resource('locations', LocationController::class);

    // Inventories
    Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
    Route::get('/inventories/create', [InventoryController::class, 'create'])->name('inventories.create');
    Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');
    Route::get('/inventories/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventories.edit');
    Route::put('/inventories/{inventory}', [InventoryController::class, 'update'])->name('inventories.update');
    Route::delete('/inventories/{inventory}', [InventoryController::class, 'destroy'])->name('inventories.destroy');

    Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])->name('inventories.show');
    Route::get('/inventories/{inventory}/items/create', [InventoryController::class, 'createItem'])->name('inventories.items.create');
    Route::post('/inventories/{inventory}/items', [InventoryController::class, 'storeItem'])->name('inventories.items.store');
    Route::get('/inventories/{inventory}/items/{item}/edit', [InventoryController::class, 'editItem'])->name('inventories.items.edit');
    Route::put('/inventories/{inventory}/items/{item}', [InventoryController::class, 'updateItem'])->name('inventories.items.update');
    Route::delete('/inventories/{inventory}/items/{item}',[InventoryController::class, 'destroyItem'])->name('inventories.items.destroy');


    // Allocates
    Route::get('/allocates', [AllocateController::class, 'index'])->name('allocates.index');

    Route::get('/allocates/hardware/create', [AllocateController::class, 'createHardware'])->name('allocates.hardware.create');
    Route::post('/allocates/hardware/store', [AllocateController::class, 'storeHardware'])->name('allocates.hardware.store');
    Route::get('/allocates/hardware/{allocateHardware}/edit', [AllocateController::class, 'editHardware'])->name('allocates.hardware.edit');
    Route::put('/allocates/hardware/{allocateHardware}/update', [AllocateController::class, 'updateHardware'])->name('allocates.hardware.update');
    Route::delete('/allocates/hardware/{allocateHardware}/destroy', [AllocateController::class, 'destroyHardware'])->name('allocates.hardware.destroy');

    Route::get('/allocates/other/create', [AllocateController::class, 'createOther'])->name('allocates.other.create');
    Route::post('/allocates/other/store', [AllocateController::class, 'storeOther'])->name('allocates.other.store');
    Route::get('/allocates/other/{allocateOther}/edit', [AllocateController::class, 'editOther'])->name('allocates.other.edit');
    Route::put('/allocates/other/{allocateOther}/update', [AllocateController::class, 'updateOther'])->name('allocates.other.update');
    Route::delete('/allocates/other/{allocateOther}/destroy', [AllocateController::class, 'destroyOther'])->name('allocates.other.destroy');
    
    Route::get('/allocates/export-pdf', [AllocateController::class, 'exportPdf'])->name('allocates.exportPdf');

    // Transfers
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers/store', [TransferController::class, 'store'])->name('transfers.store');
    Route::get('/transfers/export/pdf', [TransferController::class, 'exportPdf'])->name('transfers.exportPdf');

    // Audit Logs
    Route::get('/auditlogs', [AuditLogController::class, 'index'])->name('auditlogs.index');

    // Users
    Route::resource('users', UserController::class);
});