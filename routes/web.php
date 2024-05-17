<?php

declare(strict_types=1);

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ItemController::class, 'index'])->name('latest-items');
Route::get('/search', [ItemController::class, 'search'])->name('search-results');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('wish-list');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/mypage/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
