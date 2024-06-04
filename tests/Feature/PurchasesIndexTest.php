<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class PurchasesIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Item $item1;

    private Item $item2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $condition = Condition::factory()->create();

        $seller = User::factory()->create();
        $this->item1 = Item::factory()->for($seller, 'seller')->for($condition)->create();
        $this->item2 = Item::factory()->for($seller, 'seller')->for($condition)->create();
        Item::factory()->for($seller, 'seller')->for($condition)->create();

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->item1->id,
            'paid_at' => null,
        ]);
        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->item2->id,
        ]);
    }

    #[Test]
    public function 購入済み商品一覧をJSON形式で取得できる(): void
    {
        $this->expectsDatabaseQueryCount(7);

        $this->actingAs($this->user);
        $response = $this->getJson(route('purchases.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('meta')
            ->has('links')
            ->count('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('id', $this->item2->id)
                ->etc()
            )
        );
    }
}
