<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\ItemResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetPurchasedItems
{
    public function __invoke(User $user): AnonymousResourceCollection
    {
        $items = $user->purchasedItems()
            ->with(['seller', 'condition', 'categories', 'watchers', 'comments'])
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->paginate(10);

        return ItemResource::collection($items);
    }
}
