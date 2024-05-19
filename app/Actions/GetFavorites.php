<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\ItemResource;
use App\Models\User;

final class GetFavorites
{
    public function __invoke(User $user): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $favorites = $user->favorites()
            ->with(['seller', 'condition', 'categories', 'watchers'])
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->paginate(10);

        return ItemResource::collection($favorites);
    }
}
