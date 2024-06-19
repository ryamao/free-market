<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class AdminController extends Controller
{
    public function login(): \Inertia\Response
    {
        return Inertia::render('Admin/Login');
    }

    public function attempt(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('admin');

        $request->session()->regenerate();

        return redirect()->route('admin.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function index(): \Inertia\Response
    {
        $users = User::all();

        return Inertia::render('Admin/Index', [
            'users' => UserResource::collection($users),
        ]);
    }
}
