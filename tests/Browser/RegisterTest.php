<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\RegisterPage;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
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
}
