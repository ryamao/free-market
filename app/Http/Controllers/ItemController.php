<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetLatestItems;
use App\Actions\SearchItems;
use App\Http\Resources\ItemResource;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

final class ItemController extends Controller
{
    public function index(Request $request, GetLatestItems $action): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Inertia\Response
    {
        $items = $action();

        if ($request->wantsJson()) {
            return $items;
        } else {
            return Inertia::render('Items/Index', [
                'routeName' => 'items.index',
                'items' => $items,
            ]);
        }
    }

    public function search(Request $request, SearchItems $action): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Inertia\Response
    {
        $searchString = $request->input('q');
        if (! is_string($searchString)) {
            $searchString = '';
        }

        $items = $action($searchString);

        if ($request->wantsJson()) {
            return $items;
        } else {
            return Inertia::render('Items/Index', [
                'routeName' => 'items.search',
                'items' => $items,
                'searchString' => $searchString,
            ]);
        }
    }

    public function mylist(): \Inertia\Response
    {
        return Inertia::render('Items/Index', [
            'routeName' => 'items.mylist',
            'items' => ItemResource::collection(new LengthAwarePaginator([], 0, 10)),
        ]);
    }

    public function show(Item $item): \Inertia\Response
    {
        return Inertia::render('Items/Show', [
            'item' => ItemResource::make($item),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $conditions = Condition::all()->pluck('name');

        return Inertia::render('Items/Create', [
            'conditions' => $conditions,
        ]);
    }
}
