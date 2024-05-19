<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Item;
use App\Models\User;

final class AddToFavorites
{
    public function __invoke(User $user, Item $item): bool
    {
        if ($user->favorites->contains($item)) {
            return false;
        }

        $user->favorites()->attach($item);

        return true;
    }
}
