<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PurchasesStoreRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

final class PurchaseController extends Controller
{
    public function create(Item $item): \Inertia\Response
    {
        return Inertia::render('Items/Purchase', [
            'item' => ItemResource::make($item),
        ]);
    }

    public function store(PurchasesStoreRequest $request): RedirectResponse
    {
        throw new Exception('未実装');
    }
}
