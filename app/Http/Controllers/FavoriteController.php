<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\AddToFavorites;
use App\Actions\GetFavorites;
use App\Actions\RemoveFromFavorites;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FavoriteController extends Controller
{
    public function index(Request $request, GetFavorites $action): AnonymousResourceCollection
    {
        assert($request->user() instanceof \App\Models\User);

        return $action($request->user());
    }

    public function store(Request $request, Item $item, AddToFavorites $action): \Illuminate\Http\Response
    {
        assert($request->user() instanceof \App\Models\User);

        $success = $action($request->user(), $item);

        if ($success) {
            return response(status: 201);
        } else {
            return response(status: 409);
        }
    }

    public function destroy(Request $request, Item $item, RemoveFromFavorites $action): \Illuminate\Http\Response
    {
        assert($request->user() instanceof \App\Models\User);

        $success = $action($request->user(), $item);

        if ($success) {
            return response(status: 204);
        } else {
            return response(status: 404);
        }
    }
}
