<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\AddToFavorites;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class AddToFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private AddToFavorites $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new AddToFavorites();
    }

    #[Test]
    public function お気に入りに追加できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertTrue(($this->action)($user, $item));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertSame(1, $user->favorites()->count());
        $this->assertSame(1, $item->watchers()->count());
    }

    #[Test]
    public function 既にお気に入りに追加済みの商品は追加できない(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->favorites()->attach($item);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertFalse(($this->action)($user, $item));

        $this->assertDatabaseCount('favorites', 1);
    }
}
