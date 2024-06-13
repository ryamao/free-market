<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

final class UserController extends Controller
{
    public function dashboard(): \Inertia\Response
    {
        return Inertia::render('Dashboard');
    }

    public function destroy(User $user): \Illuminate\Http\Response
    {
        if ($user->isDeleted()) {
            return response(status: 409);
        }

        $user->markAsDeleted();

        return response()->noContent();
    }
}
