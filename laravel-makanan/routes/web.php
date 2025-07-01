<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute Autentikasi (TIDAK di dalam middleware 'auth')
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rute yang dapat diakses TANPA autentikasi
Route::get('/home', [HomeController::class, 'index'])->name('home'); // Pindahkan rute /home ke sini

// Rute yang memerlukan autentikasi (DI DALAM middleware 'auth')
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute-rute manajemen lainnya yang memerlukan login
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::post('/kategori', [KategoriController::class, 'store']);
    Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit']);
    Route::post('/kategori/update/{id}', [KategoriController::class, 'update']);
    Route::post('/kategori/delete/{id}', [KategoriController::class, 'destroy']);

    Route::get('/menu', [MenuController::class, 'index']);
    Route::post('/menu', [MenuController::class, 'store']);
    Route::get('/menu/kategori/{id}', [MenuController::class, 'filterByKategori']);
    Route::get('/menu/edit/{id}', [MenuController::class, 'edit']);
    Route::post('/menu/update/{id}', [MenuController::class, 'update']);
    Route::post('/menu/delete/{id}', [MenuController::class, 'destroy']);

    // Order Routes
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Rute untuk Laporan Pesanan Aktif
    Route::get('/laporan-pesanan', [OrderController::class, 'showOrders'])->name('laporanpesanan.list');

    // Rute untuk Histori Pesanan
    Route::get('/histori-pesanan', [OrderController::class, 'showHistory'])->name('historipesanan.list');
    Route::get('/histori-pesanan/rekap-print', [OrderController::class, 'printRekap'])->name('historipesanan.printRekap');

    // Rute untuk memperbarui status pesanan
    Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');

    // Rute Manajemen Akses (placeholder)
    Route::get('/manajemen-akses', function () {
        return view('manajemen_akses'); // Anda perlu membuat view ini
    })->name('manajemen.akses');
});