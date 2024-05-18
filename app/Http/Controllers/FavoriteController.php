<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\AddToFavorite;
use App\Models\Item;

final class FavoriteController extends Controller
{
    public function store(Item $item, AddToFavorite $action): \Illuminate\Http\Response
    {
        $user = auth()->user();

        assert($user !== null);

        $success = $action($user, $item);

        if ($success) {
            return response(status: 201);
        } else {
            return response(status: 409);
        }
    }
}