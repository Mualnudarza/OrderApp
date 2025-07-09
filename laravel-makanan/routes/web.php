<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController; // Pastikan ini diimpor
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ManajemenAksesController; // Pastikan ini diimpor
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
// Rute utama diberi nama 'home'
Route::get('/', [HomeController::class, 'index'])->name('home'); // Daftar menu publik
// Rute /home juga mengarah ke index, tapi tanpa nama rute duplikat
Route::get('/home', [HomeController::class, 'index']);


// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute untuk Admin (Kategori & Menu)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/kategori/{id}', [MenuController::class, 'filterByKategori']);
        Route::get('/menu/edit/{id}', [MenuController::class, 'edit']);
        Route::post('/menu/update/{id}', [MenuController::class, 'update']);
        Route::post('/menu/delete/{id}', [MenuController::class, 'destroy']);
    });

    // Rute untuk Kasir (Order, Laporan Pesanan, Histori Pesanan)
    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::post('/order', [OrderController::class, 'store'])->name('order.store');
        // Rute untuk laporan pesanan aktif (status pending)
        Route::get('/laporan-pesanan', [OrderController::class, 'showPendingOrders'])->name('laporanpesanan.list');
        // Rute untuk update status pesanan
        Route::post('/order/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');

        // Rute untuk histori pesanan (status completed/cancelled)
        Route::get('/histori-pesanan', [OrderController::class, 'showHistory'])->name('historipesanan.list');
        Route::get('/histori-pesanan/rekap-print', [OrderController::class, 'printRekap'])->name('historipesanan.printRekap');
    });

    // Rute untuk Master (Manajemen Akses)
    Route::middleware(['role:master'])->group(function () {
        Route::get('/manajemen-akses', [ManajemenAksesController::class, 'index'])->name('manajemen.akses');
        Route::post('/manajemen-akses/store', [ManajemenAksesController::class, 'store'])->name('manajemen.akses.store');
        Route::post('/manajemen-akses/update-role/{user}', [ManajemenAksesController::class, 'updateRole'])->name('manajemen.akses.update');
        Route::delete('/manajemen-akses/delete/{user}', [ManajemenAksesController::class, 'destroy'])->name('manajemen.akses.destroy');

        // Rute baru untuk mengambil histori perubahan akses (AJAX)
        Route::get('/manajemen-akses/users/{user}/role-history', [ManajemenAksesController::class, 'getUserRoleHistory'])->name('manajemen.akses.roleHistory');
    });
});

