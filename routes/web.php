<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/import', [DashboardController::class, 'import'])->name('import');
    Route::post('/export', [DashboardController::class, 'export'])->name('export');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
