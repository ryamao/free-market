<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;

final class DirectMailController extends Controller
{
    public function create(User $user): \Inertia\Response
    {
        return Inertia::render('Admin/Email', [
            'user' => UserResource::make($user),
        ]);
    }
}
