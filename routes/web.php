<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Items/Index'))->name('latest-items');
Route::get('/search', fn () => Inertia::render('Items/Index'))->name('search-results');
Route::get('/mylist', fn () => Inertia::render('Items/Index'))->name('wish-list');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/mypage/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
