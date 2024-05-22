<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class ProfileEditPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('profile.edit', absolute: false);
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
            '@title' => 'h2',
            '@name-label' => 'label[for="name"]',
            '@postcode-label' => 'label[for="postcode"]',
            '@address-label' => 'label[for="address"]',
            '@building-label' => 'label[for="building"]',
            '@name-input' => 'input[id="name"]',
            '@postcode-input' => 'input[id="postcode"]',
            '@address-input' => 'input[id="address"]',
            '@building-input' => 'input[id="building"]',
        ];
    }
}
