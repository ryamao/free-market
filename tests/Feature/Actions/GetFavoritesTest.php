<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\GetFavorites;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class GetFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private GetFavorites $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new GetFavorites();
    }

    #[Test]
    public function お気に入り商品を取得する(): void
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $items = Item::factory()->count(3)->recycle($condition)->create();
        Item::factory()->count(2)->create();

        $items->each(fn ($item) => $user->favorites()->attach($item));

        $response = ($this->action)($user);

        $this->assertCount($items->count(), $response);
        $this->assertEquals(
            $items->sortBy([['created_at', 'desc'], ['name', 'asc']])->pluck('id'),
            collect($response)->pluck('id')
        );
    }

    #[Test]
    public function お気に入り商品を取得する_ページネーション(): void
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $items = Item::factory()->count(15)->recycle($condition)->create();

        $items->each(fn ($item) => $user->favorites()->attach($item));

        $response = ($this->action)($user);

        $this->assertCount(10, $response);
    }
}
