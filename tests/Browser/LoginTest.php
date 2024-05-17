<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

final class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    #[Test]
    public function ログインページの要素が表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage())
                ->assertSeeIn('@title', 'ログイン')
                ->assertSeeIn('@email-label', 'メールアドレス')
                ->assertPresent('@email')
                ->assertSeeIn('@password-label', 'パスワード')
                ->assertPresent('@password')
                ->assertSeeIn('@login-button', 'ログインする')
                ->assertSeeIn('@register-link', '会員登録はこちら');
        });
    }

    #[Test]
    public function ログインページからログインする(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage())
                ->type('@email', 'test@example.com')
                ->type('@password', 'password')
                ->press('@login-button')
                ->waitForRoute('dashboard')
                ->logout();
        });
    }

    #[Test]
    public function バリデーションエラー時にエラーメッセージが表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new LoginPage();
            $browser->visit($page)
                ->assertDontSee('Eメールは必須項目です。')
                ->assertDontSee('パスワードは必須項目です。')
                ->press('@login-button')
                ->waitForText('Eメールは必須項目です。')
                ->waitForText('パスワードは必須項目です。')
                ->waitForLocation($page->url());
        });
    }

    #[Test]
    public function 会員登録ページに遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage())
                ->click('@register-link')
                ->waitForRoute('register');
        });
    }
}
