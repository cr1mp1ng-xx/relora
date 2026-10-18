<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/reset-password', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    // Route::get('/profil', [ProfilController::class, 'index'])->name('profil');

    Route::get('/barang-saya', [ProdukController::class, 'index'])->name('barang.saya');
    Route::get('/barang-saya/{produk}/edit', [ProdukController::class, 'edit'])->name('barang.edit');
    Route::patch('/barang-saya/{produk}', [ProdukController::class, 'update'])->name('barang.update');
    Route::patch('/barang-saya/{produk}/status', [ProdukController::class, 'ubahStatus'])->name('barang.status');
    Route::get('/jual', [ProdukController::class, 'create'])->name('jual');
    Route::post('/jual', [ProdukController::class, 'store'])->name('jual.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});