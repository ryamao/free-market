<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetLatestItems;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

final class ItemController extends Controller
{
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Inertia\Response
    {
        $items = app(GetLatestItems::class)();

        if ($request->wantsJson()) {
            return $items;
        }

        return Inertia::render('Items/Index', [
            'items' => $items,
        ]);
    }

    public function search(): \Inertia\Response
    {
        return Inertia::render('Items/Index', [
            'items' => ItemResource::collection(new LengthAwarePaginator([], 0, 10)),
            'searchString' => request()->input('q'),
        ]);
    }

    public function mylist(): \Inertia\Response
    {
        return Inertia::render('Items/Index', [
            'items' => ItemResource::collection(new LengthAwarePaginator([], 0, 10)),
        ]);
    }
}
