<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ClienteVipController;
use App\Http\Controllers\Admin\OrdenAdminController;
use App\Http\Controllers\Admin\AnuncioController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Kiosco\KioscoController;
use App\Http\Controllers\Cocina\CocinaController;

// Login
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::middleware('auth.rol:administrador')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('productos', ProductoController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('vips', ClienteVipController::class);
    Route::resource('ordenes', OrdenAdminController::class);
    Route::resource('anuncios', AnuncioController::class);
    Route::resource('usuarios', UsuarioController::class);
});

// Kiosco
Route::middleware('auth.rol:administrador,cajero')->prefix('kiosco')->name('kiosco.')->group(function () {
    Route::get('/', [KioscoController::class, 'index'])->name('index');
    Route::post('/buscar-vip', [KioscoController::class, 'buscarVip'])->name('kiosco.buscar-vip');
    Route::post('/crear-orden', [KioscoController::class, 'crearOrden'])->name('kiosco.crear-orden');
});

// Cocina
Route::middleware('auth.rol:administrador,cocina')->prefix('cocina')->name('cocina.')->group(function () {
    Route::get('/', [CocinaController::class, 'index'])->name('index');
    Route::post('/orden/{orden}/{estado}', [CocinaController::class, 'actualizar'])->name('actualizar');
});