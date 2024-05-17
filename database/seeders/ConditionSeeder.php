<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

final class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            '未使用',
            '良好',
            '使用感あり',
            '傷や汚れあり',
            'ジャンク品',
        ];

        foreach ($names as $name) {
            Condition::create(['name' => $name]);
        }
    }
}
