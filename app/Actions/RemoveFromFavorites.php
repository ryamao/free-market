<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Item;
use App\Models\User;

final class RemoveFromFavorites
{
    public function __invoke(User $user, Item $item): bool
    {
        if ($user->favorites->doesntContain($item)) {
            return false;
        }

        $user->favorites()->detach($item);

        return true;
    }
}
