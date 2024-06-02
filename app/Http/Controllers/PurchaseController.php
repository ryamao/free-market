<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class PurchaseController extends Controller
{
    public function create(Request $request, Item $item): \Inertia\Response|RedirectResponse
    {
        assert($request->user() !== null);

        if ($item->isSold()) {
            return redirect()->route('items.show', $item);
        }

        $purchase = $item->purchases()->firstWhere('user_id', $request->user()->id);
        if ($purchase === null) {
            $payment = $this->createPayment($request->user(), $item);
            $purchase = $item->purchases()->create([
                'user_id' => $request->user()->id,
                'payment_intent_id' => $payment->asStripePaymentIntent()->id,
            ]);
        } else {
            $payment = $purchase->payment();
            assert($payment !== null);
        }

        return Inertia::render('Items/Purchase', [
            'item' => ItemResource::make($item),
            'clientSecret' => $payment->clientSecret(),
        ]);
    }

    private function createPayment(User $user, Item $item): \Laravel\Cashier\Payment
    {
        return $user->payWith(
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
                'metadata' => [
                    'item_id' => $item->id,
                ],
            ]
        );
    }
}
