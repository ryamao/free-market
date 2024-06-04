<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
final class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'payment_intent_id' => '',
            'payment_status' => 'succeeded',
            'client_secret' => '',
            'paid_at' => now(),
        ];
    }
}
