<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

final class UpdateProfile
{
    public function __invoke(ProfileUpdateRequest $request): void
    {
        assert($request->user() !== null);

        $user = $request->user();

        $user->fill($request->only([
            'name',
            'postcode',
            'prefecture',
            'address',
            'building',
        ]));

        $imagePath = $this->saveImage($request, $user);
        if ($imagePath !== null) {
            $user->image_url = Storage::url($imagePath);
        }

        $user->save();
    }

    private function saveImage(ProfileUpdateRequest $request, User $user): ?string
    {
        $image = $request->image;
        if ($image === null) {
            return null;
        }

        $imageName = $user->id.'.'.$image->extension();
        $imagePath = $image->storeAs('user_images', $imageName);

        return $imagePath ?: null;
    }
}
