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
// Rute utama diberi nama 'home'
Route::get('/', [HomeController::class, 'index'])->name('home'); // Daftar menu publik
// Rute /home juga mengarah ke index, tapi tanpa nama rute duplikat
Route::get('/home', [HomeController::class, 'index']);

// Grup Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Arahkan berdasarkan peran
        if (Auth::user()->isAdmin()) {
            return redirect()->route('laporanpesanan.list');
        } elseif (Auth::user()->isKasir()) {
            return redirect()->route('order.index');
        } elseif (Auth::user()->isMaster()) {
            return redirect()->route('manajemen.akses');
        }
        return view('dashboard'); // Default jika tidak ada peran yang cocok
    })->name('dashboard');

    // Rute untuk Kasir
    Route::middleware(['role:kasir,admin,master'])->group(function () { // Kasir, Admin, Master bisa akses pemesanan
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::post('/order', [OrderController::class, 'store'])->name('order.store');
        Route::get('/laporan-pesanan', [OrderController::class, 'showPendingOrders'])->name('laporanpesanan.list');
        Route::post('/order/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    });

    // Rute untuk Admin
    Route::middleware(['role:admin,master'])->group(function () { // Admin dan Master bisa akses manajemen menu & kategori
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.list');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::post('/kategori/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::post('/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/menu', [MenuController::class, 'index'])->name('menu.list');
        Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
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
        Route::post('/manajemen-akses/store', [ManajemenAksesController::class, 'store'])->name('manajemen.akses.store');
        Route::post('/manajemen-akses/update-role/{user}', [ManajemenAksesController::class, 'updateRole'])->name('manajemen.akses.update');
        Route::delete('/manajemen-akses/delete/{user}', [ManajemenAksesController::class, 'destroy'])->name('manajemen.akses.destroy');

        // Rute ini tidak lagi diperlukan karena histori dimuat langsung di index
        // Route::get('/manajemen-akses/users/{user}/role-history', [ManajemenAksesController::class, 'getUserRoleHistory'])->name('manajemen.akses.roleHistory');
    });
});
