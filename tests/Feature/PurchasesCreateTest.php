<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class PurchasesCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;

    private User $buyer;

    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create();

        $this->buyer = User::factory()->create();
        $this->buyer->createAsStripeCustomer();

        $this->item = Item::factory()->for($this->seller, 'seller')->create();
    }

    #[Test]
    public function 購入画面を表示できる(): void
    {
        $this->actingAs($this->buyer);
        $response = $this->get(route('purchases.create', $this->item));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Purchase')
            ->has('item', fn (AssertableInertia $page) => $page
                ->has('data', fn (AssertableInertia $page) => $page
                    ->where('id', $this->item->id)
                    ->has('seller', fn (AssertableInertia $page) => $page
                        ->where('id', $this->seller->id)
                        ->etc()
                    )
                    ->etc()
                )
            )
            ->has('clientSecret')
        );
    }

    #[Test]
    public function 一人のユーザーが同一商品の購入画面を2回表示したときに同じクライアントシークレットを使用する(): void
    {
        $clientSecret1 = null;

        $this->actingAs($this->buyer);

        $response1 = $this->get(route('purchases.create', $this->item));
        $response1->assertOk();
        $response1->assertInertia(function (AssertableInertia $page) use (&$clientSecret1) {
            return $page
                ->where('clientSecret', function (string $value) use (&$clientSecret1) {
                    $clientSecret1 = $value;

                    return true;
                })
                ->etc();
        });

        $response2 = $this->get(route('purchases.create', $this->item));
        $response2->assertOk();
        $response2->assertInertia(fn (AssertableInertia $page) => $page
            ->where('clientSecret', $clientSecret1)
            ->etc()
        );
    }

    #[Test]
    public function 販売終了した商品の購入画面を表示できない(): void
    {
        $this->item->update(['sold_at' => now()]);

        $this->actingAs($this->buyer);
        $response = $this->get(route('purchases.create', $this->item));

        $response->assertRedirect(route('items.show', $this->item));
    }
}
