<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', function () {
        if (Auth::guard('admin')->attempt([
            'email' => 'admin@example.com',
            'password' => 'zxcvbnmasdfghjklqwertyuiop',
        ])) {
            return redirect()->route('admin.index');
        } else {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    })->name('admin.login');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin', fn () => \Inertia\Inertia::render('Dashboard'))->name('admin.index');

    Route::get('/admin/logout', function () {
        Auth::guard('admin')->logout();

        return redirect()->route('home.index');
    })->name('admin.logout');
});
