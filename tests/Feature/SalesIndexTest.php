<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class SalesIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $condition = Condition::factory()->create(['name' => '新品・未使用']);

        $this->user = User::factory()->create();
        $this->user->items()->create([
            'name' => 'Item 1',
            'price' => 1000,
            'condition_id' => $condition->id,
            'description' => 'Description 1',
            'image_url' => 'https://example.com/image1.jpg',
        ]);

        $user2 = User::factory()->create();
        $user2->items()->create([
            'name' => 'Item 2',
            'price' => 2000,
            'condition_id' => $condition->id,
            'description' => 'Description 2',
            'image_url' => 'https://example.com/image2.jpg',
        ]);
    }

    #[Test]
    public function 出品した商品の一覧を取得できる(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('sales.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->count('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('name', 'Item 1')
                ->where('price', 1000)
                ->where('condition', '新品・未使用')
                ->where('description', 'Description 1')
                ->where('image_url', 'https://example.com/image1.jpg')
                ->etc()
            )
            ->etc()
        );
    }
}
