<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetItemsForSale
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        assert($request->user() !== null);

        $items = $request->user()
            ->items()
            ->with(['seller', 'condition', 'categories', 'watchers', 'comments'])
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->paginate(10);

        return ItemResource::collection($items);
    }
}
