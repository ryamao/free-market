<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\PreparePayment;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class PurchaseController extends Controller
{
    public function create(Request $request, Item $item, PreparePayment $action): \Inertia\Response|RedirectResponse
    {
        assert($request->user() !== null);

        if ($item->isSold()) {
            return redirect()->route('items.show', $item);
        }

        $payment = $action($request->user(), $item);

        return Inertia::render('Items/Purchase', [
            'item' => ItemResource::make($item),
            'clientSecret' => $payment->clientSecret(),
        ]);
    }
}
