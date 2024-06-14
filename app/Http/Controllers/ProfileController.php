<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\UpdateProfile;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

final class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): \Inertia\Response
    {
        assert($request->user() instanceof \App\Models\User);

        return Inertia::render('Profile/Edit', [
            'user' => UserResource::make($request->user()),
            'profile' => ProfileResource::make($request->user()),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, UpdateProfile $action): RedirectResponse
    {
        assert($request->user() instanceof \App\Models\User);

        $action($request, $request->user());

        return Redirect::route('dashboard');
    }
}
