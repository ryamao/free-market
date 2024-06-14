<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('admin.login', absolute: false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
        $browser->assertSeeIn('h2', '管理者ログイン');
        $browser->assertSeeIn('@email-label', 'メールアドレス');
        $browser->assertSeeIn('@password-label', 'パスワード');
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
            '@email-label' => 'label[for="email"]',
            '@password-label' => 'label[for="password"]',
            '@email-input' => 'input#email',
            '@password-input' => 'input#password',
        ];
    }
}
