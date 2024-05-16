<?php

namespace Tests\Browser\Items;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Items;
use Tests\DuskTestCase;

class IndexTest extends DuskTestCase
{
    use DatabaseTruncation;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    #[Test]
    public function 商品一覧ページにアクセスすると新着商品が表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->assertSeeIn('@main-nav', '新着商品');
        });
    }

    #[Test]
    public function 検索フォームで商品を検索できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->assertDontSeeIn('@main-nav', '検索結果')
                ->type('@search', 'テスト')
                ->keys('@search', '{enter}')
                ->waitForRoute('search-results')
                ->assertQueryStringHas('q', 'テスト')
                ->assertInputValue('@search', 'テスト')
                ->assertSeeIn('@main-nav', '検索結果')
                ->clickLink('新着商品')
                ->waitForRoute('latest-items')
                ->assertDontSeeIn('@main-nav', '検索結果');
        });
    }

    #[Test]
    public function ゲスト時はマイリストが表示されない(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->assertDontSeeIn('@main-nav', 'マイリスト');
        });
    }

    #[Test]
    public function ログイン中はマイリストを表示できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(new Items\IndexPage())
                ->clickLink('マイリスト')
                ->waitForRoute('wish-list')
                ->clickLink('新着商品')
                ->waitForRoute('latest-items')
                ->logout();
        });
    }

    #[Test]
    public function ゲスト時に商品一覧ページからログインページへ遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->clickLink('ログイン')
                ->waitForRoute('login');
        });
    }

    #[Test]
    public function ゲスト時に商品一覧ページから会員登録ページへ遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->clickLink('会員登録')
                ->waitForRoute('register');
        });
    }

    #[Test]
    public function ログイン中に商品一覧ページからログアウトできる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(new Items\IndexPage())
                ->clickLink('ログアウト')
                ->waitForRoute('latest-items');
        });

        $this->assertGuest();
    }

    #[Test]
    public function ログイン中に商品一覧ページからマイページへ遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(new Items\IndexPage())
                ->clickLink('マイページ')
                ->waitForRoute('dashboard')
                ->logout();
        });
    }
}
