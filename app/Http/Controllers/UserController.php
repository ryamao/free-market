<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Inertia\Inertia;

final class UserController extends Controller
{
    public function dashboard(): \Inertia\Response
    {
        return Inertia::render('Dashboard', [
            'user' => UserResource::make(auth()->user()),
            'routeName' => 'dashboard',
        ]);
    }
}
