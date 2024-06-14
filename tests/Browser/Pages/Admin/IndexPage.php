<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class IndexPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('admin.index', absolute: false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
        $browser->assertSeeIn('h2', '登録ユーザー一覧');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
