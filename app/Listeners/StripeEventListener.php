<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

final class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        switch ($event->payload['type']) {
            case 'payment_intent.requires_action':
                $this->markAsPaid($event->payload['data']['object']);
                break;
            case 'payment_intent.succeeded':
                $this->markAsPaid($event->payload['data']['object']);
                break;
            default:
                break;
        }
    }

    // @phpstan-ignore-next-line
    private function markAsPaid(array $paymentIntent): void
    {
        $item = Item::firstWhere('id', $paymentIntent['metadata']['item_id']);
        if ($item === null) {
            Log::error('Item not found', ['item_id' => $paymentIntent['metadata']['item_id']]);

            return;
        }
        if ($item->isSold()) {
            return;
        }

        $user = User::firstWhere('stripe_id', $paymentIntent['customer']);
        if ($user === null) {
            Log::error('User not found', ['stripe_id' => $paymentIntent['customer']]);

            return;
        }

        $purchase = $item->purchases()->firstWhere('user_id', $user->id);
        if ($purchase === null) {
            Log::error('Purchase not found', ['item_id' => $item->id, 'user_id' => $user->id]);

            return;
        }

        $purchase->markAsPaid();
    }
}
