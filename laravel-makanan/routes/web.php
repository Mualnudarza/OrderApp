<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController; // Import OrderController

Route::get('/', function () {
    return view('dashboard');
});


Route::get('/home', [HomeController::class, 'index']);


Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);

Route::get('/menu', [MenuController::class, 'index']);
Route::post('/menu', [MenuController::class, 'store']);

Route::get('/menu/kategori/{id}', [MenuController::class, 'filterByKategori']);


// Kategori
Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit']);
Route::post('/kategori/update/{id}', [KategoriController::class, 'update']);
Route::post('/kategori/delete/{id}', [KategoriController::class, 'destroy']);

// Menu
Route::get('/menu/edit/{id}', [MenuController::class, 'edit']);
Route::post('/menu/update/{id}', [MenuController::class, 'update']);
Route::post('/menu/delete/{id}', [MenuController::class, 'destroy']);

// Order Routes
Route::get('/order', [OrderController::class, 'index'])->name('order.index'); // Halaman pemesanan menu
Route::post('/order', [OrderController::class, 'store'])->name('order.store'); // Menyimpan pesanan baru

// Mengubah rute untuk daftar pesanan menjadi '/laporan-pesanan' dan nama rute menjadi 'laporanpesanan.list'
Route::get('/laporan-pesanan', [OrderController::class, 'showOrders'])->name('laporanpesanan.list'); // Menampilkan daftar semua pesanan
Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus'); // Memperbarui status pesanan

