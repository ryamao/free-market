<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (array_slice(range('A', 'Z'), 0, 10) as $alphabet) {
            Category::create(['name' => "カテゴリ$alphabet"]);
        }
    }
}
