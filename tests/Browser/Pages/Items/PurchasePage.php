<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Items;

use App\Models\Item;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class PurchasePage extends Page
{
    public function __construct(private Item $item)
    {
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('purchases.create', ['item' => $this->item], absolute: false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
        $browser->assertSee($this->item->name);
        $browser->assertSee('¥'.number_format((float) $this->item->price));
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@item-name' => 'h2',
        ];
    }
}
