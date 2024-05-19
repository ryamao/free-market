<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class FavoritesDestroyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function エンドポイントからお気に入りを削除できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->favorites()->attach($item);

        $response = $this->actingAs($user)
            ->delete(route('mylist.destroy', $item));

        $response->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    #[Test]
    public function お気に入り未登録の場合は404エラーを返す(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('mylist.destroy', $item));

        $response->assertNotFound();
    }

    #[Test]
    public function 未ログインの場合はログインページにリダイレクトされる(): void
    {
        $item = Item::factory()->create();

        $response = $this->delete(route('mylist.destroy', $item));

        $response->assertRedirect(route('login'));
    }
}
