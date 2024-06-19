<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CommentsIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $seller = User::factory()->create();
        $this->item = Item::factory()->for($seller, 'seller')->create();

        $this->item->comments()->create([
            'user_id' => $this->user->id,
            'content' => 'コメント',
        ]);
    }

    #[Test]
    public function コメント一覧を取得できる(): void
    {
        $response = $this->get(route('comments.index', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Comments/Index')
            ->has('item', fn (AssertableInertia $page) => $page
                ->has('data', fn (AssertableInertia $page) => $page
                    ->where('id', $this->item->id)
                    ->etc()
                )
            )
            ->has('comments', fn (AssertableInertia $page) => $page
                ->has('data', 1)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->has('user', fn (AssertableInertia $page) => $page
                        ->where('id', $this->user->id)
                        ->etc()
                    )
                    ->where('content', 'コメント')
                    ->etc()
                )
                ->etc()
            )
        );
    }

    #[Test]
    public function コメント一覧にユーザー情報が含まれている(): void
    {
        $response = $this->get(route('comments.index', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('comments', fn (AssertableInertia $page) => $page
                ->where('data.0.user.id', $this->user->id)
                ->etc()
            )
        );
    }

    #[Test]
    public function 削除済みユーザーのコメントにはユーザー情報が含まれない(): void
    {
        $this->user->delete();

        $response = $this->get(route('comments.index', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('comments', fn (AssertableInertia $page) => $page
                ->where('data.0.user', null)
                ->etc()
            )
        );
    }
}
