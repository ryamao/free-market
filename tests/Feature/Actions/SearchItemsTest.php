<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\GetLatestItems;
use App\Actions\SearchItems;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class SearchItemsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $conditionNew = Condition::factory()->create(['name' => 'New']);
        $conditionUsed = Condition::factory()->create(['name' => 'Used']);

        $categoryA = Category::factory()->create(['name' => 'A']);
        $categoryB = Category::factory()->create(['name' => 'B']);
        $categoryC = Category::factory()->create(['name' => 'C']);

        $items = Item::factory()
            ->count(5)
            ->for(User::factory(), 'seller')
            ->sequence(
                ['name' => 'AAA', 'description' => '111', 'condition_id' => $conditionNew->id],
                ['name' => 'BBB', 'description' => '222', 'condition_id' => $conditionUsed->id],
                ['name' => 'CCC', 'description' => '333', 'condition_id' => $conditionNew->id],
                ['name' => 'DDD', 'description' => '444', 'condition_id' => $conditionUsed->id],
                ['name' => 'EEE', 'description' => '555', 'condition_id' => $conditionNew->id],
            )
            ->state(fn () => ['created_at' => fake()->dateTimeBetween('-1 week')])
            ->create();

        $items[0]?->categories()?->attach($categoryA);
        $items[1]?->categories()?->attach($categoryB);
        $items[2]?->categories()?->attach($categoryC);
        $items[3]?->categories()?->attach($categoryA);
        $items[4]?->categories()?->attach($categoryB);
    }

    #[Test]
    public function 購入可能な商品が存在しない場合は空のリストを返す(): void
    {
        Item::query()->delete();
        $action = new SearchItems();
        $items = $action(null);

        $this->assertEmpty($items);
    }

    #[Test]
    public function 検索文字列が空の場合は新着商品を返す(): void
    {
        $getLatestItems = new GetLatestItems();
        $expected = $getLatestItems();

        $searchItems = new SearchItems();
        $actual = $searchItems(null);

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function 商品名と説明文を検索する(): void
    {
        $expected = Item::where('name', 'AAA')
            ->orWhere('name', 'CCC')
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->get();

        $action = new SearchItems();
        $actual = $action('A 3');

        $this->assertCount(2, $actual);
        $this->assertEquals($expected->pluck('id'), collect($actual)->pluck('id'));
    }

    #[Test]
    public function 状態名で検索する(): void
    {
        $condition = Condition::where('name', 'New')->firstOrFail();
        $expected = Item::whereBelongsTo($condition)
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->get();

        $action = new SearchItems();
        $actual = $action('condition:New');

        $this->assertCount(3, $actual);
        $this->assertEquals($expected->pluck('id'), collect($actual)->pluck('id'));
    }

    #[Test]
    public function カテゴリ名で検索する(): void
    {
        $expected = Item::whereHas('categories', fn ($query) => $query->where('name', 'B'))
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->get();

        $action = new SearchItems();
        $actual = $action('category:B');

        $this->assertCount(2, $actual);
        $this->assertEquals($expected->pluck('id'), collect($actual)->pluck('id'));
    }

    #[Test]
    public function 状態名とカテゴリ名で検索する(): void
    {
        $expected = Item::where('name', 'DDD')->get();

        $action = new SearchItems();
        $actual = $action('condition:Used category:A');

        $this->assertCount(1, $actual);
        $this->assertEquals($expected->pluck('id'), collect($actual)->pluck('id'));
    }
}
