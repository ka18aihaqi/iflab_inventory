<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AllocateController;
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
    Route::resource('inventories', InventoryController::class);

    // Allocates
    Route::get('/allocates', [AllocateController::class, 'index'])->name('allocates.index');
    Route::get('/allocates/create', [AllocateController::class, 'create'])->name('allocates.create');

    Route::post('/allocates/hardware/store', [AllocateController::class, 'storeHardware'])->name('allocates.hardware.store');
    Route::get('/allocates/hardware/{allocateHardware}/edit', [AllocateController::class, 'editHardware'])->name('allocates.hardware.edit');
    Route::put('/allocates/hardware/{allocateHardware}/update', [AllocateController::class, 'updateHardware'])->name('allocates.hardware.update');
    Route::delete('/allocates/hardware/{allocateHardware}/destroy', [AllocateController::class, 'destroyHardware'])->name('allocates.hardware.destroy');

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

    // Users
    Route::resource('users', UserController::class);
});