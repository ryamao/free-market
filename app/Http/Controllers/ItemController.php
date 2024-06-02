<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetLatestItems;
use App\Actions\SearchItems;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;

final class ItemController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $searchString = $request->input('q');
        if (is_string($searchString) && $searchString !== '') {
            return app(SearchItems::class)($searchString);
        } else {
            return app(GetLatestItems::class)();
        }
    }

    public function show(Item $item): \Inertia\Response
    {
        $payment = null;
        if (auth()->check()) {
            $purchase = $item->purchases()->firstWhere('user_id', auth()->id());
            if ($purchase) {
                $payment = [
                    'status' => $purchase->payment_status,
                    'clientSecret' => $purchase->client_secret,
                ];
            }
        }

        return Inertia::render('Items/Show', [
            'item' => ItemResource::make($item),
            'payment' => $payment,
        ]);
    }
}
