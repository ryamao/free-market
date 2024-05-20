<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

final class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        $users = User::all();

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
