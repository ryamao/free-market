<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\CommentResource;
use App\Models\Item;

final class GetComments
{
    public function __invoke(Item $item): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $comments = $item
            ->comments()
            ->with('user')
            ->orderBy('created_at')
            ->get();

        return CommentResource::collection($comments);
    }
}
