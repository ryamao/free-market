<?php

declare(strict_types=1);

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// トップページ

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/search', [ItemController::class, 'search'])->name('items.search');
Route::middleware('auth')
    ->get('/mylist', [FavoriteController::class, 'index'])->name('mylist.index');

// 商品詳細ページ

Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::middleware('auth')->group(function () {
    Route::post('/mylist/{item}', [FavoriteController::class, 'store'])->name('mylist.store');
    Route::delete('/mylist/{item}', [FavoriteController::class, 'destroy'])->name('mylist.destroy');
});

// コメントページ

Route::get('/comments/{item}', [CommentController::class, 'index'])->name('comments.index');
Route::middleware('auth')
    ->post('/comments/{item}', [CommentController::class, 'store'])->name('comments.store');

// 商品購入ページ

Route::middleware('auth')->group(function () {
    Route::get('/purchase/{item}', fn () => Inertia::render('Welcome'))->name('purchase.create');
});

// マイページ

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/mypage/purchases', fn () => Inertia::render('Dashboard', ['routeName' => 'dashboard.purchases']))->name('dashboard.purchases');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
});

// プロフィール編集ページ

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// 出品ページ

Route::middleware('auth')->group(function () {
    Route::get('/sell', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sell', [SaleController::class, 'store'])->name('sales.store');
});

require __DIR__.'/auth.php';
