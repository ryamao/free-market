<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;

final class AddComment
{
    public function __invoke(User $user, Item $item, string $content): void
    {
        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => $content,
        ]);
    }
}
