<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\ItemResource;
use App\Models\Item;

final class GetLatestItems
{
    public function __invoke(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $items = Item::with(['seller', 'condition', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ItemResource::collection($items);
    }
}
