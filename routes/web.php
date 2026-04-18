<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Kiosco\KioscoController;
use App\Http\Controllers\Cocina\CocinaController;

// Login
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::middleware('auth.rol:administrador')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

// Kiosco
Route::middleware('auth.rol:administrador,cajero')->prefix('kiosco')->group(function () {
    Route::get('/', [KioscoController::class, 'index'])->name('kiosco.index');
});

// Cocina
Route::middleware('auth.rol:administrador,cocina')->prefix('cocina')->group(function () {
    Route::get('/', [CocinaController::class, 'index'])->name('cocina.index');
});
