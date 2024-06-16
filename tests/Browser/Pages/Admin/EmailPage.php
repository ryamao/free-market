<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Admin;

use App\Models\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

final class EmailPage extends Page
{
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('direct-mails.create', $this->user, false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
        $browser->assertSeeIn('@name-label', '宛名');
        $browser->assertSeeIn('@title-label', '件名');
        $browser->assertSeeIn('@content-label', '本文');
        $browser->assertValue('@name-input', ($this->user->name ?? '匿名ユーザー').' 様');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@name-label' => 'label[for="name"]',
            '@title-label' => 'label[for="title"]',
            '@content-label' => 'label[for="content"]',
            '@name-input' => 'input#name',
            '@title-input' => 'input#title',
            '@content-input' => 'textarea#content',
            '@submit-button' => 'button[type="submit"]',
        ];
    }
}
