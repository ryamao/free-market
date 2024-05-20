<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CommentStoreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function エンドポイントからコメントを追加できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('comments.store', $item), [
                'content' => 'コメント1',
            ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'コメント1',
        ]);
    }

    #[Test]
    public function ゲストユーザーはコメントを追加できない(): void
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', $item), [
            'content' => 'コメント1',
        ]);

        $response->assertRedirectToRoute('login');

        $this->assertDatabaseEmpty('comments');
    }
}
