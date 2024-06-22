<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ItemsShowTest extends TestCase
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
    }

    #[Test]
    public function 商品詳細ページを表示できる(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('items.show', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Show')
            ->has('item', fn (AssertableInertia $item) => $item
                ->has('data', fn (AssertableInertia $data) => $data
                    ->where('id', $this->item->id)
                    ->etc()
                )
            )
            ->where('payment', null)
        );
    }

    #[Test]
    public function 購入済み商品の詳細ページでは支払い情報を取得できる(): void
    {
        $this->item->purchases()->create([
            'user_id' => $this->user->id,
            'payment_intent_id' => 'pi_123456',
            'payment_status' => 'succeeded',
            'client_secret' => 'client_secret',
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('items.show', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Show')
            ->has('item', fn (AssertableInertia $item) => $item
                ->has('data', fn (AssertableInertia $data) => $data
                    ->where('id', $this->item->id)
                    ->etc()
                )
            )
            ->has('payment', fn (AssertableInertia $payment) => $payment
                ->where('status', 'succeeded')
                ->where('clientSecret', 'client_secret')
            )
        );
    }

    #[Test]
    public function 購入済み商品の詳細ページでもゲストは支払い情報を取得できない(): void
    {
        $this->item->purchases()->create([
            'user_id' => $this->user->id,
            'payment_intent_id' => 'pi_123456',
            'payment_status' => 'succeeded',
            'client_secret' => 'client_secret',
        ]);

        $response = $this->get(route('items.show', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Show')
            ->has('item', fn (AssertableInertia $item) => $item
                ->has('data', fn (AssertableInertia $data) => $data
                    ->where('id', $this->item->id)
                    ->etc()
                )
            )
            ->where('payment', null)
        );
    }
}
