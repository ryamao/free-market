<?php

declare(strict_types=1);

namespace Tests\Browser\Items;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Items;
use Tests\DuskTestCase;

final class IndexTest extends DuskTestCase
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
    public function 商品一覧ページにアクセスすると新着商品ページが表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->assertTitleContains('商品一覧')
                ->assertSeeIn('@main-nav', '新着商品');
        });
    }

    #[Test]
    public function 購入可能な商品が存在しない場合は新着商品が表示されない(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Items\IndexPage())
                ->assertNotPresent('@item-list > li');
        });
    }

    #[Test]
    public function 購入可能な商品が存在する場合は新着商品が表示される(): void
    {
        $this->seed(TestDataSeeder::class);

        $items = Item::orderByDesc('created_at')->orderBy('name')->get();

        $this->browse(function (Browser $browser) use ($items) {
            $browser->resize(768, 600)
                ->visit(new Items\IndexPage())
                ->assertAttribute(
                    '@item-list > li:nth-of-type(1) > a',
                    'href',
                    route('items.show', $items[0]?->id)
                )
                ->assertAttribute(
                    '@item-list > li:nth-of-type(2) > a',
                    'href',
                    route('items.show', $items[1]?->id)
                )
                ->assertAttribute(
                    '@item-list > li:nth-of-type(10) > a',
                    'href',
                    route('items.show', $items[9]?->id)
                )
                ->assertNotPresent('@item-list > li:nth-of-type(11) > a')
                ->scrollTo('@item-list > li:nth-of-type(11)')
                ->waitFor('@item-list > li:nth-of-type(11) > a')
                ->assertAttribute(
                    '@item-list > li:nth-of-type(11) > a',
                    'href',
                    route('items.show', $items[10]?->id)
                );
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
                ->waitForRoute('items.search')
                ->assertQueryStringHas('q', 'テスト')
                ->assertInputValue('@search', 'テスト')
                ->assertSeeIn('@main-nav', '検索結果')
                ->clickLink('新着商品')
                ->waitForRoute('items.index')
                ->assertDontSeeIn('@main-nav', '検索結果');
        });
    }

    #[Test]
    public function ゲスト時はマイリストが表示されない(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit(new Items\IndexPage())
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
                ->waitForRoute('items.mylist')
                ->clickLink('新着商品')
                ->waitForRoute('items.index')
                ->logout();
        });
    }

    #[Test]
    public function ゲスト時に商品一覧ページからログインページへ遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit(new Items\IndexPage())
                ->clickLink('ログイン')
                ->waitForRoute('login');
        });
    }

    #[Test]
    public function ゲスト時に商品一覧ページから会員登録ページへ遷移できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit(new Items\IndexPage())
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
                ->waitForRoute('items.index');
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
