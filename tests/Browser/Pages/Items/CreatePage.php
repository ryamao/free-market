<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Items;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class CreatePage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('items.create', absolute: false);
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
            '@categories-label' => 'label[for="categories"]',
            '@condition-label' => 'label[for="condition"]',
            '@name-label' => 'label[for="name"]',
            '@description-label' => 'label[for="description"]',
            '@price-label' => 'label[for="price"]',
            '@categories-input' => 'input[id="categories"]',
            '@condition-select' => 'select[id="condition"]',
            '@name-input' => 'input[id="name"]',
            '@description-textarea' => 'textarea[id="description"]',
            '@price-input' => 'input[id="price"]',
            '@submit-button' => 'button[type="submit"]',
        ];
    }
}
