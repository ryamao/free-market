<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class FavoriteStoreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function お気に入りに追加できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('mylist.store', $item));

        $response->assertStatus(201);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
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

        $response = $this->actingAs($user)
            ->post(route('mylist.store', $item));

        $response->assertStatus(409);

        $this->assertDatabaseCount('favorites', 1);
    }

    #[Test]
    public function 未ログインユーザーはお気に入りに追加できない(): void
    {
        $item = Item::factory()->create();

        $response = $this->post(route('mylist.store', $item));

        $response->assertRedirect(route('login'));
    }
}
