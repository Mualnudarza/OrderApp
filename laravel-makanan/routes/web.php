<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ManajemenAksesController;
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
Route::get('/', [HomeController::class, 'index'])->name('home'); // Daftar menu publik
Route::get('/home', [HomeController::class, 'index'])->name('home'); // Tetap pertahankan /home juga

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute Dashboard (Admin & Master bisa akses)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute untuk Kasir (Pemesanan & Laporan Pesanan)
    Route::middleware(['role:admin,kasir,master'])->group(function () { // Master juga bisa akses
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::post('/order', [OrderController::class, 'store'])->name('order.store');
        Route::get('/laporan-pesanan', [OrderController::class, 'showOrders'])->name('laporanpesanan.list');
        Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    });

    // Rute untuk Admin (Kategori, Menu, Histori Pesanan, Laporan Rekap)
    Route::middleware(['role:admin,master'])->group(function () { // Master juga bisa akses
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit']);
        Route::post('/kategori/update/{id}', [KategoriController::class, 'update']);
        Route::post('/kategori/delete/{id}', [KategoriController::class, 'destroy']);

        Route::get('/menu', [MenuController::class, 'index'])->name('menu');
        Route::post('/menu', [MenuController::class, 'store']);
        Route::get('/menu/kategori/{id}', [MenuController::class, 'filterByKategori']);
        Route::get('/menu/edit/{id}', [MenuController::class, 'edit']);
        Route::post('/menu/update/{id}', [MenuController::class, 'update']);
        Route::post('/menu/delete/{id}', [MenuController::class, 'destroy']);

        Route::get('/histori-pesanan', [OrderController::class, 'showHistory'])->name('historipesanan.list');
        Route::get('/histori-pesanan/rekap-print', [OrderController::class, 'printRekap'])->name('historipesanan.printRekap');
    });

    // Rute untuk Master (Manajemen Akses)
    Route::middleware(['role:master'])->group(function () {
        Route::get('/manajemen-akses', [ManajemenAksesController::class, 'index'])->name('manajemen.akses');
        Route::post('/manajemen-akses/store', [ManajemenAksesController::class, 'store'])->name('manajemen.akses.store'); // Rute baru untuk tambah user
        Route::post('/manajemen-akses/update-role/{user}', [ManajemenAksesController::class, 'updateRole'])->name('manajemen.akses.update');
        Route::delete('/manajemen-akses/delete/{user}', [ManajemenAksesController::class, 'destroy'])->name('manajemen.akses.destroy'); // Rute baru untuk hapus user
    });
});