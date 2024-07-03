<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetItemsBySeller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;

final class UserController extends Controller
{
    public function dashboard(): \Inertia\Response
    {
        return Inertia::render('Dashboard');
    }

    public function destroy(User $user): \Illuminate\Http\Response
    {
        $user->delete();

        return response()->noContent();
    }

    public function show(User $user): \Inertia\Response
    {
        return Inertia::render('User/Show', [
            'user' => UserResource::make($user),
        ]);
    }

    public function sales(User $user, GetItemsBySeller $action): AnonymousResourceCollection
    {
        return $action($user);
    }
}
