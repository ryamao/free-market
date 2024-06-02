<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Item;
use App\Models\User;
use InvalidArgumentException;
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
                $this->handlePaid($event, 'requires_action');
                break;
            case 'payment_intent.succeeded':
                $this->handlePaid($event, 'succeeded');
                break;
            default:
                break;
        }
    }

    private function handlePaid(WebhookReceived $event, string $status): void
    {
        $paymentIntent = $event->payload['data']['object'];

        $item = $this->findItem($paymentIntent['metadata']['item_id']);
        $user = $this->findUser($paymentIntent['customer']);
        $purchase = $this->findPurchase($user, $item);

        $purchase->setPaymentStatus($status);

        if ($item->isSold()) {
            return;
        }

        $item->markAsSold($purchase->paid_at);
        $purchase->markAsPaid();
    }

    private function findItem(string $itemId): \App\Models\Item
    {
        $item = Item::firstWhere('id', $itemId);
        if ($item === null) {
            throw new InvalidArgumentException("Item not found with ID: $itemId");
        }

        return $item;
    }

    private function findUser(string $stripeId): \App\Models\User
    {
        $user = User::firstWhere('stripe_id', $stripeId);
        if ($user === null) {
            throw new InvalidArgumentException("User not found with Stripe ID: $stripeId");
        }

        return $user;
    }

    private function findPurchase(\App\Models\User $user, \App\Models\Item $item): \App\Models\Purchase
    {
        $purchase = $item->purchases()->firstWhere('user_id', $user->id);
        if ($purchase === null) {
            throw new InvalidArgumentException("Purchase not found for user ID: {$user->id} and item ID: {$item->id}");
        }

        return $purchase;
    }
}
