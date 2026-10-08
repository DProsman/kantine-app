<?php

use App\Http\Controllers\BeheerController;
use App\Http\Controllers\BestelController;
use App\Http\Controllers\BestellingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [BestelController::class, 'index'])->name('bestellen');
    Route::post('/bestellen', [BestelController::class, 'store'])->name('bestellen.store');

    Route::get('/bestellingen', [BestellingController::class, 'index'])->name('bestellingen.index');
    Route::get('/bestellingen/{bestelling}/bewerken', [BestellingController::class, 'edit'])->name('bestellingen.edit');
    Route::put('/bestellingen/{bestelling}', [BestellingController::class, 'update'])->name('bestellingen.update');

    Route::middleware('beheerder')->prefix('beheer')->name('beheer.')->group(function () {
        Route::get('/', [BeheerController::class, 'index'])->name('index');
        Route::patch('/bestellingen/{bestelling}/status', [BeheerController::class, 'status'])->name('status');
        Route::get('/producten', [ProductController::class, 'index'])->name('producten');
        Route::post('/producten', [ProductController::class, 'store'])->name('producten.store');
    });
});
