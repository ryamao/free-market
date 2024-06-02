<?php

declare(strict_types=1);

namespace Tests\Browser\Items;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Items\PurchasePage;
use Tests\DuskTestCase;

final class PurchaseTest extends DuskTestCase
{
    use DatabaseTruncation;

    private PurchasePage $page;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->createAsStripeCustomer();

        $item = Item::factory()->create();
        $this->page = new PurchasePage($item);
    }

    #[Test]
    public function 商品購入ページを表示できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->logout();
        });
    }
}
