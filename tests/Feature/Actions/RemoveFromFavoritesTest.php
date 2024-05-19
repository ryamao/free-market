<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\RemoveFromFavorites;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class RemoveFromFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private RemoveFromFavorites $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new RemoveFromFavorites();
    }

    #[Test]
    public function お気に入りから削除できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->favorites()->attach($item);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertTrue(($this->action)($user, $item));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertSame(0, $user->favorites()->count());
        $this->assertSame(0, $item->watchers()->count());
    }

    #[Test]
    public function お気に入りに追加されていない商品は削除できない(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->assertFalse(($this->action)($user, $item));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
