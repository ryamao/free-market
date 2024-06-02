<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Item;
use App\Models\User;

final class PreparePayment
{
    public function __invoke(User $user, Item $item): \Laravel\Cashier\Payment
    {
        $purchase = $item->purchases()->firstWhere('user_id', $user->id);
        if ($purchase) {
            $payment = $purchase->payment();
            assert($payment !== null);

            return $payment;
        }

        $payment = $user->payWith(
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
        $purchase = $item->purchases()->create([
            'user_id' => $user->id,
            'payment_intent_id' => $payment->asStripePaymentIntent()->id,
            'payment_status' => $payment->asStripePaymentIntent()->status,
            'client_secret' => $payment->clientSecret(),
        ]);

        return $payment;
    }
}
