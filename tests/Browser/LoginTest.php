<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

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
}
