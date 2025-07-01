<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('dashboard');
});

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