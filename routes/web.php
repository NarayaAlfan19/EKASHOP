<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Halaman Katalog Utama Publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Detail Produk Bunga
Route::get('/flower/{slug}', [HomeController::class, 'show'])->name('flower.show');