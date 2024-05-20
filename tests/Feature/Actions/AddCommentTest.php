<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\AddComment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    private AddComment $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new AddComment();
    }

    #[Test]
    public function コメントを追加できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        ($this->action)($user, $item, 'コメント1');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'コメント1',
        ]);
    }
}
