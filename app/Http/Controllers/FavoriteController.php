<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\AddToFavorites;
use App\Actions\GetFavorites;
use App\Actions\RemoveFromFavorites;
use App\Models\Item;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FavoriteController extends Controller
{
    public function index(GetFavorites $action): AnonymousResourceCollection
    {
        assert(auth()->user() !== null);

        return $action(auth()->user());
    }

    public function store(Item $item, AddToFavorites $action): \Illuminate\Http\Response
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

    public function destroy(Item $item, RemoveFromFavorites $action): \Illuminate\Http\Response
    {
        $user = auth()->user();
        assert($user !== null);

        $success = $action($user, $item);

        if ($success) {
            return response(status: 204);
        } else {
            return response(status: 404);
        }
    }
}
