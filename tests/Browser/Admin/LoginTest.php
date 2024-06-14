<?php

declare(strict_types=1);

namespace Tests\Browser\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Admin\LoginPage;
use Tests\DuskTestCase;

final class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

    private LoginPage $page;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = new LoginPage();
        $this->admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    #[Test]
    public function 管理者用ログインページを表示できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->page);
        });
    }

    #[Test]
    public function 管理者はログインできる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->page);
            $browser->type('@email-input', $this->admin->email);
            $browser->type('@password-input', 'password');
            $browser->press('ログインする');
            $browser->waitForRoute('admin.index');
        });
    }

    #[Test]
    public function ログイン中は管理ページにリダイレクトする(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin');
            $browser->visitRoute('admin.login');
            $browser->waitForRoute('admin.index');
        });
    }
}
