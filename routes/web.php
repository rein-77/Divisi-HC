<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratMasukDisposisiController;
use App\Http\Controllers\SuratKeluarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Surat Masuk Routes
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::get('surat-masuk/{id}/disposisi', [SuratMasukController::class, 'getDisposisi'])->name('surat-masuk.disposisi');
    
    // Surat Masuk Disposisi Routes
    Route::resource('surat-masuk-disposisi', SuratMasukDisposisiController::class);

    Route::resource('surat-keluar', SuratKeluarController::class);
});

require __DIR__.'/auth.php';
