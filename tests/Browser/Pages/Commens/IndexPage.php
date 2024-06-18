<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Commens;

use App\Models\Item;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class IndexPage extends Page
{
    public function __construct(private readonly Item $item)
    {
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('comments.index', ['item' => $this->item], false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
        $browser->assertPresent('img[src="'.$this->item->image_url.'"]');
        $browser->assertSeeIn('@item-name', $this->item->name);
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
            '@comment-list' => 'main ul',
            '@content-input' => 'textarea',
        ];
    }
}
