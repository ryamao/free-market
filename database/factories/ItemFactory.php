<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Condition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
final class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = implode(' ', Arr::wrap(fake()->words()));

        return [
            'seller_id' => User::factory(),
            'condition_id' => Condition::factory(),
            'name' => $name,
            'description' => Arr::join(Arr::wrap(fake()->sentences(fake()->numberBetween(1, 5))), PHP_EOL),
            'price' => fake()->numberBetween(1, 100).'00',
            'image_url' => 'https://via.placeholder.com/150?text='.urlencode(mb_substr($name, 0, 7).'...'),
        ];
    }
}
