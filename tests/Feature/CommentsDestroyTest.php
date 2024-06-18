<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CommentsDestroyTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    private Comment $comment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();

        $seller = User::factory()->create();
        $item = Item::factory()->create(['seller_id' => $seller->id]);

        $user = User::factory()->create();
        $this->comment = Comment::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => 'コメント',
        ]);
    }

    #[Test]
    public function 管理者はコメントを削除できる(): void
    {
        $this->actingAs($this->admin, 'admin');
        $response = $this->delete(route('comments.destroy', $this->comment));

        $response->assertStatus(204);
        $this->assertSoftDeleted('comments', ['id' => $this->comment->id]);
    }
}
