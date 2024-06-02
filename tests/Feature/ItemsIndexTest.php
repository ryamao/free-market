<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ItemsIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function データベースに商品がない場合(): void
    {
        $this->expectsDatabaseQueryCount(1);

        $response = $this->getJson(route('items.index'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 0)
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('total', 0)
                ->where('per_page', 10)
                ->where('current_page', 1)
                ->where('last_page', 1)
                ->where('path', route('items.index'))
                ->etc()
            )
        );
    }

    #[Test]
    public function データベースに商品がある場合(): void
    {
        $this->seed(TestDataSeeder::class);
        $items = Item::orderByDesc('created_at')->orderBy('name')->get();
        $count = $items->count();

        $this->expectsDatabaseQueryCount(7);

        $response = $this->getJson(route('items.index'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('id', $items[0]?->id)
                ->etc()
            )
            ->has('data.9', fn (AssertableJson $json) => $json
                ->where('id', $items[9]?->id)
                ->etc()
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('total', $count)
                ->where('per_page', 10)
                ->where('from', 1)
                ->where('to', 10)
                ->where('current_page', 1)
                ->where('last_page', (int) ceil($count / 10))
                ->etc()
            )
        );
    }

    #[Test]
    public function 商品一覧ページの2ページ目を表示する(): void
    {
        $this->seed(TestDataSeeder::class);
        $items = Item::orderByDesc('created_at')->orderBy('name')->get();

        $this->expectsDatabaseQueryCount(7);

        $response = $this->getJson(route('items.index', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('id', $items[10]?->id)
                ->etc()
            )
            ->has('data.9', fn (AssertableJson $json) => $json
                ->where('id', $items[19]?->id)
                ->etc()
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('per_page', 10)
                ->where('from', 11)
                ->where('to', 20)
                ->where('current_page', 2)
                ->etc()
            )
        );
    }

    #[Test]
    public function 商品一覧ページの最終ページを表示する(): void
    {
        $this->seed(TestDataSeeder::class);
        $items = Item::orderByDesc('created_at')->orderBy('name')->get();
        $count = $items->count();
        $lastPage = (int) ceil($count / 10);

        $this->expectsDatabaseQueryCount(7);

        $response = $this->getJson(route('items.index', ['page' => $lastPage]));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', $count % 10)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('id', $items[$count - $count % 10]?->id)
                ->etc()
            )
            ->has('data.'.($count % 10 - 1), fn (AssertableJson $json) => $json
                ->where('id', $items[$count - 1]?->id)
                ->etc()
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('per_page', 10)
                ->where('from', $count - $count % 10 + 1)
                ->where('to', $count)
                ->where('current_page', $lastPage)
                ->etc()
            )
        );
    }

    #[Test]
    public function お気に入り登録数がレスポンスに含まれている(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->getJson(route('items.index'));
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('data.0.id', $item->id)
            ->where('data.0.favorite_count', 0)
            ->where('data.0.is_favorite', false)
            ->etc()
        );

        $user->favorites()->attach($item);

        $response = $this->actingAs($user)->getJson(route('items.index'));
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('data.0.id', $item->id)
            ->where('data.0.favorite_count', 1)
            ->where('data.0.is_favorite', true)
            ->etc()
        );
    }
}
