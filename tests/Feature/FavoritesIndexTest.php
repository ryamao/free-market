<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class FavoritesIndexTest extends TestCase
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
        $this->user->favorites()->attach($this->item);

        Item::factory()->for($seller, 'seller')->create();
    }

    #[Test]
    public function お気に入り商品一覧を取得できる(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('favorites.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1, fn (AssertableJson $json) => $json
                ->where('id', $this->item->id)
                ->etc()
            )
            ->has('meta')
            ->has('links')
        );
    }
}
