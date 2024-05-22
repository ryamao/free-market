<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

final class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): \Inertia\Response
    {
        assert(auth()->user() !== null);

        return Inertia::render('Profile/Edit', [
            'user' => UserResource::make(auth()->user()),
            'profile' => ProfileResource::make(auth()->user()),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        assert($request->user() !== null);

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        assert($request->user() !== null);

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
