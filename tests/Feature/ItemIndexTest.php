<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
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

        $response = $this->get(route('latest-items'));

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
                    ->where('path', route('latest-items', absolute: true))
                    ->etc()
                )
            )
        );
    }

    #[Test]
    public function データベースに商品がある場合は商品一覧ページに商品を表示する(): void
    {
        $this->seed(TestDataSeeder::class);
        $items = Item::orderBy('created_at', 'desc')->get();
        $count = $items->count();

        // items、sellers、conditions、categories、item_categoryの5つのテーブルからデータを取得する
        $this->expectsDatabaseQueryCount(5);

        $response = $this->get(route('latest-items'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', 10)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $items->firstOrFail()->id)
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
        $items = Item::orderBy('created_at', 'desc')->get();

        $this->expectsDatabaseQueryCount(5);

        $response = $this->get(route('latest-items', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', 10)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $items[10]?->id)
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
        $items = Item::orderBy('created_at', 'desc')->get();
        $count = $items->count();
        $lastPage = (int) ceil($count / 10);

        $this->expectsDatabaseQueryCount(5);

        $response = $this->get(route('latest-items', ['page' => $lastPage]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->has('items', fn (AssertableInertia $page) => $page
                ->has('data', $count % 10)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $items[$count - $count % 10]?->id)
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
}
