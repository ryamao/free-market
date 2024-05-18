<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function データベースに商品がない場合は商品一覧ページに商品を表示しない(): void
    {
        $this->expectsDatabaseQueryCount(1);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', 0)
                ->has('links')
                ->has('meta', fn (AssertableInertia $page) => $page
                    ->where('total', 0)
                    ->where('per_page', 10)
                    ->where('current_page', 1)
                    ->where('last_page', 1)
                    ->where('path', route('items.index', absolute: true))
                    ->etc()
                )
            )
        );
    }

    #[Test]
    public function データベースに商品がある場合は商品一覧ページに商品を表示する(): void
    {
        $this->seed(TestDataSeeder::class);
        $items = Item::orderByDesc('created_at')->orderBy('name')->get();
        $count = $items->count();

        // 6つのテーブルからデータを取得する
        // items, sellers, conditions, categories, item_category, favorites
        $this->expectsDatabaseQueryCount(6);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', 10)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $items[0]?->id)
                    ->etc()
                )
                ->has('data.9', fn (AssertableInertia $page) => $page
                    ->where('id', $items[9]?->id)
                    ->etc()
                )
                ->has('links')
                ->has('meta', fn (AssertableInertia $page) => $page
                    ->where('total', $count)
                    ->where('per_page', 10)
                    ->where('from', 1)
                    ->where('to', 10)
                    ->where('current_page', 1)
                    ->where('last_page', (int) ceil($count / 10))
                    ->etc()
                )
            )
        );
    }

    #[Test]
    public function 商品一覧ページの2ページ目を表示する(): void
    {
        $this->seed(TestDataSeeder::class);
        $items = Item::orderByDesc('created_at')->orderBy('name')->get();

        $this->expectsDatabaseQueryCount(6);

        $response = $this->get(route('items.index', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', 10)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $items[10]?->id)
                    ->etc()
                )
                ->has('data.9', fn (AssertableInertia $page) => $page
                    ->where('id', $items[19]?->id)
                    ->etc()
                )
                ->has('links')
                ->has('meta', fn (AssertableInertia $page) => $page
                    ->where('per_page', 10)
                    ->where('from', 11)
                    ->where('to', 20)
                    ->where('current_page', 2)
                    ->etc()
                )
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

        $this->expectsDatabaseQueryCount(6);

        $response = $this->get(route('items.index', ['page' => $lastPage]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', $count % 10)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $items[$count - $count % 10]?->id)
                    ->etc()
                )
                ->has('data.'.($count % 10 - 1), fn (AssertableInertia $page) => $page
                    ->where('id', $items[$count - 1]?->id)
                    ->etc()
                )
                ->has('links')
                ->has('meta', fn (AssertableInertia $page) => $page
                    ->where('per_page', 10)
                    ->where('from', $count - $count % 10 + 1)
                    ->where('to', $count)
                    ->where('current_page', $lastPage)
                    ->etc()
                )
            )
        );
    }

    #[Test]
    public function お気に入り登録数がレスポンスに含まれている(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->get(route('items.index'));
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $item->id)
                    ->where('favorite_count', 0)
                    ->etc()
                )
                ->etc()
            )
        );

        $user->favorites()->attach($item);

        $response = $this->get(route('items.index'));
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $item->id)
                    ->where('favorite_count', 1)
                    ->etc()
                )
                ->etc()
            )
        );
    }
}
