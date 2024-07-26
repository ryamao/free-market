<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()->count(4)->create();

        $conditions = Condition::all();
        $items = Item::factory()
            ->count(123)
            ->recycle($users)
            ->recycle($conditions)
            ->state(fn () => [
                'created_at' => fake()->dateTimeBetween('-1 month'),
            ])
            ->create();
        foreach ($items as $item) {
            $name = 'Item'.$item->id;
            $item->update([
                'name' => $name,
                'image_url' => 'https://via.placeholder.com/150?text='.urlencode($name),
            ]);
        }

        $categories = Category::all();
        foreach ($items as $item) {
            foreach ($categories->random(2) as $category) {
                $item->categories()->attach($category);
            }
        }
    }
}
