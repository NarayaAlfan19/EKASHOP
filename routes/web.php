<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// CONTROLLERS PUBLIK
// ==========================================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// ==========================================
// CONTROLLERS ADMIN (Tetap di dalam folder Admin)
// ==========================================
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlowerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FlowerTypeController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\BranchStockController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\SettingController;

// ==========================================
// ALIAS ROUTE LOGIN GLOBAL (Mengatasi error Route [login] not defined saat logout)
// ==========================================
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ==========================================
// 1. ROUTE PUBLIK (Pengunjung Tanpa Login)
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bunga/{slug}', [HomeController::class, 'show'])->name('flower.show');

// Fitur Keranjang & Checkout diaktifkan
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/whatsapp', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('throttle:10,1');


// ==========================================
// 2. ROUTE ADMIN (Manajemen Toko)
// ==========================================

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest Admin (Belum Login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    });

    // Protected Admin (Wajib Login & Role Admin)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD Utama
        Route::resource('bunga', FlowerController::class)->parameters(['bunga' => 'flower']);

        // CRUD Master Data
        Route::resource('kategori', CategoryController::class)->parameters(['kategori' => 'category']);
        Route::resource('jenis', FlowerTypeController::class)->parameters(['jenis' => 'type']);
        Route::resource('acara', OccasionController::class)->parameters(['acara' => 'occasion']);
        Route::resource('warna', ColorController::class)->parameters(['warna' => 'color']);
        Route::resource('tag', TagController::class);

        // CRUD Cabang & Manajemen Stok
        Route::resource('cabang', AdminBranchController::class);
        Route::get('/cabang/{id}/stok', [BranchStockController::class, 'index'])->name('cabang.stock.index');
        Route::post('/cabang/{id}/stok', [BranchStockController::class, 'update'])->name('cabang.stock.update');

        // Riwayat Permintaan (Hanya Baca)
        Route::get('/permintaan', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/permintaan/{id}', [InquiryController::class, 'show'])->name('inquiries.show');

        // Pengaturan Toko & Profil
        Route::get('/pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
        
        Route::get('/profil', [AuthController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');
    });
});