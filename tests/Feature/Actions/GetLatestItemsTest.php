<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\GetLatestItems;
use App\Models\Item;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class GetLatestItemsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function データベースに商品がない場合は空のコレクションを返す(): void
    {
        $action = new GetLatestItems();
        $items = $action();

        $this->assertEmpty($items);
    }

    #[Test]
    public function データベースに商品がある場合は商品のコレクションを返す(): void
    {
        $this->seed(TestDataSeeder::class);
        $expected = Item::orderBy('created_at', 'desc')->limit(10)->get();

        $action = new GetLatestItems();
        $actual = $action();

        $this->assertEquals($expected->pluck('id'), collect($actual)->pluck('id'));
    }
}
