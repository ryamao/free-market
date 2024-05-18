<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;

final class AddToFavorite
{
    public function __invoke(User $user, Item $item): bool
    {
        $exists = Favorite::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->exists();

        if ($exists) {
            return false;
        }

        $user->favorites()->attach($item);

        return true;
    }
}
