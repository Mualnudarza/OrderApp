<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

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

// Rute untuk Laporan Pesanan Aktif (hanya status pending)
Route::get('/laporan-pesanan', [OrderController::class, 'showOrders'])->name('laporanpesanan.list');

// Rute untuk Histori Pesanan (status completed atau cancelled, dengan filter)
Route::get('/histori-pesanan', [OrderController::class, 'showHistory'])->name('historipesanan.list');

// Rute untuk mencetak rekap histori pesanan (dengan filter)
Route::get('/histori-pesanan/rekap-print', [OrderController::class, 'printRekap'])->name('historipesanan.printRekap');

// Rute untuk memperbarui status pesanan
Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');

