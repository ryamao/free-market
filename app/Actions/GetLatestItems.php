<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\ItemResource;
use App\Models\Item;

final class GetLatestItems
{
    public function __invoke(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $items = Item::with(['seller', 'condition', 'categories', 'watchers', 'comments'])
            ->whereNull('sold_at')
            ->whereHas('seller', fn ($query) => $query->whereNull('deleted_at'))
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->paginate(10);

        return ItemResource::collection($items);
    }
}
