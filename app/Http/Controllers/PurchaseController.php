<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class PurchaseController extends Controller
{
    public function create(Request $request, Item $item): \Inertia\Response
    {
        assert($request->user() !== null);

        $payment = $request->user()->payWith(
            $item->price,
            ['card', 'konbini', 'customer_balance'],
            [
                'amount' => $item->price,
                'currency' => 'jpy',
                'payment_method_data' => ['type' => 'customer_balance'],
                'payment_method_options' => [
                    'customer_balance' => [
                        'funding_type' => 'bank_transfer',
                        'bank_transfer' => ['type' => 'jp_bank_transfer'],
                    ],
                ],
            ]);

        return Inertia::render('Items/Purchase', [
            'item' => ItemResource::make($item),
            'clientSecret' => $payment->clientSecret(),
        ]);
    }
}
