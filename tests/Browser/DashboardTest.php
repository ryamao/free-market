<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\DashboardPage;
use Tests\DuskTestCase;

final class DashboardTest extends DuskTestCase
{
    use DatabaseTruncation;

    private DashboardPage $page;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = new DashboardPage();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function マイページが表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->assertSeeIn('@user-name', $this->user->name ?? '')
                ->logout();
        });
    }

    #[Test]
    public function プロフィール編集ページに遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->clickLink('プロフィールを編集')
                ->waitForRoute('profile.edit')
                ->logout();
        });
    }
}
