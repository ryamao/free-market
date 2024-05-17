<?php

declare(strict_types=1);

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\RegisterPage;
use Tests\DuskTestCase;

final class RegisterTest extends DuskTestCase
{
    use DatabaseTruncation;

    #[Test]
    public function 会員登録ページの要素が表示されている(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterPage())
                ->assertSeeIn('@title', '会員登録')
                ->assertSeeIn('@email-label', 'メールアドレス')
                ->assertPresent('@email')
                ->assertSeeIn('@password-label', 'パスワード')
                ->assertPresent('@password')
                ->assertSeeIn('@register-button', '登録する')
                ->assertSeeIn('@login-link', 'ログインはこちら');
        });
    }

    #[Test]
    public function 会員登録ができる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterPage())
                ->type('@email', 'test@example.com')
                ->type('@password', 'password')
                ->press('@register-button')
                ->waitForRoute('profile.edit')
                ->logout();
        });
    }

    #[Test]
    public function バリデーションエラー時にエラーメッセージが表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new RegisterPage();
            $browser->visit($page)
                ->assertDontSee('Eメールは必須項目です。')
                ->assertDontSee('パスワードは必須項目です。')
                ->press('@register-button')
                ->waitForText('Eメールは必須項目です。')
                ->waitForText('パスワードは必須項目です。')
                ->waitForLocation($page->url());
        });
    }

    #[Test]
    public function ログインページに遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new RegisterPage();
            $browser->visit($page)
                ->click('@login-link')
                ->waitForRoute('login');
        });
    }
}
