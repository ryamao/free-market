<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Items;

use App\Models\Item;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class ShowPage extends Page
{
    /**
     * Create a new page instance.
     */
    public function __construct(private Item $item)
    {
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('items.show', $this->item, absolute: false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@app-logo' => 'header h1 img[alt="COACHTECH"]',
            '@top-page-link' => 'header h1 a',
            '@search-input' => 'header input[type="search"]',
            '@item-image' => 'main img[src="'.$this->item->image_url.'"]',
        ];
    }
}
