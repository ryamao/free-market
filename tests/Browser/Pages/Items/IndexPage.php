<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Items;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class IndexPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/';
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
            '@search' => 'header input[type="search"]',
            '@main-nav' => 'main nav ul',
            '@item-list' => 'main div ul',
        ];
    }
}
