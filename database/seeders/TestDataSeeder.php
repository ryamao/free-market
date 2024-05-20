<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class TestDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ConditionSeeder::class,
            CategorySeeder::class,
        ]);

        $users = User::factory()->count(4)->create();

        $conditions = Condition::all();
        $items = Item::factory()
            ->count(35)
            ->recycle($users)
            ->recycle($conditions)
            ->state(fn () => ['created_at' => fake()->dateTimeBetween('-1 month')])
            ->create();

        $categories = Category::all();
        foreach ($items as $item) {
            $item->categories()->attach($categories->random());
            $item->categories()->attach($categories->random());
        }

        foreach (Item::all() as $item) {
            foreach (range(1, random_int(0, 10)) as $i) {
                $item->comments()->create([
                    'user_id' => $users->random()->id,
                    'content' => fake()->paragraph(random_int(1, 3)),
                ]);
            }
        }
    }
}
