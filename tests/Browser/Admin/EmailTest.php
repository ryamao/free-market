<?php

declare(strict_types=1);

namespace Tests\Browser\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Admin\EmailPage;
use Tests\DuskTestCase;

final class EmailTest extends DuskTestCase
{
    use DatabaseTruncation;

    private EmailPage $page;

    private Admin $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->user = User::factory()->create();
        $this->page = new EmailPage($this->user);
    }

    #[Test]
    public function 管理者がメール作成ページを表示できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin');
            $browser->visit($this->page);
        });
    }

    #[Test]
    public function メールを送信できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin');
            $browser->visit($this->page);
            $browser->type('@title-input', 'テストメール');
            $browser->type('@content-input', 'テストメール本文');
            $browser->press('メールを送信する');
            $browser->waitForDialog();
            $browser->acceptDialog();
            $browser->waitForDialog();
            $browser->acceptDialog();
            $browser->waitForRoute('admin.index');
        });
    }
}
