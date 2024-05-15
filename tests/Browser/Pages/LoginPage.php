<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/login';
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
            '@email-label' => 'label[for=email]',
            '@email' => 'input#email',
            '@password-label' => 'label[for=password]',
            '@password' => 'input#password',
            '@login-button' => 'button[type=submit]',
            '@register-link' => 'a[href="'.route('register').'"]',
        ];
    }
}
