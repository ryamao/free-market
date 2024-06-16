<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SendDirectMail;
use App\Http\Requests\DirectMailsStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

final class DirectMailController extends Controller
{
    public function create(User $user): \Inertia\Response
    {
        return Inertia::render('Admin/Email', [
            'user' => UserResource::make($user),
        ]);
    }

    public function store(DirectMailsStoreRequest $request, User $user, SendDirectMail $action): RedirectResponse
    {
        $action($request, $user);

        return redirect()->route('admin.index');
    }
}
