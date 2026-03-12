<?php

use Illuminate\Support\Facades\Route;

// Ana sayfa - giriş sayfasına yönlendir
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth rotaları
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin paneli - auth gerekli
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

    // Şubeler
    Route::resource('/admin/branches', \App\Http\Controllers\Admin\BranchController::class);
    // Müşteriler
    Route::resource('/admin/customers', \App\Http\Controllers\Admin\CustomerController::class);
    // Sevkiyatlar
    Route::resource('/admin/shipments', \App\Http\Controllers\Admin\ShipmentController::class);
    // Kullanıcılar
    Route::resource('/admin/users', \App\Http\Controllers\Admin\UserController::class);
    // Finans
    Route::get('/admin/finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('admin.finance');
    // Ayarlar
    Route::get('/admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update']);
    // Canlı destek toggle
    Route::post('/admin/support/toggle', [\App\Http\Controllers\Admin\SettingsController::class, 'toggleSupport']);
});

// Müşteri takip (public)
Route::get('/track/{number}', [\App\Http\Controllers\TrackingController::class, 'show'])->name('track');
Route::get('/track', [\App\Http\Controllers\TrackingController::class, 'index'])->name('track.index');
