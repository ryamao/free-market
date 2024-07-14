<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class UsersSalesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $condition = Condition::factory()->create();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->for($this->user, 'seller')->for($condition)->create();

        $other = User::factory()->create();
        Item::factory()->for($other, 'seller')->for($condition)->create();
    }

    #[Test]
    public function 出品商品一覧を取得できる(): void
    {
        $response = $this->get(route('users.sales', $this->user));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->count('data', 1)
            ->where('data.0.id', $this->item->id)
            ->has('links')
            ->has('meta')
        );
    }
}
