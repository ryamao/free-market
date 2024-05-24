<?php

declare(strict_types=1);

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/purchase', fn () => Inertia::render('Dashboard', ['user' => auth()->user(), 'routeName' => 'purchase.index']))->name('purchase.index');
});

// プロフィール編集ページ

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// 出品ページ

Route::middleware('auth')->group(function () {
    Route::get('/exhibit', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
});

require __DIR__.'/auth.php';
