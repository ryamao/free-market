<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DirectMailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'attempt'])->name('admin.attempt');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/users/{user}/direct-mails', [DirectMailController::class, 'create'])->name('direct-mails.create');
    Route::post('/users/{user}/direct-mails', [DirectMailController::class, 'store'])->name('direct-mails.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
