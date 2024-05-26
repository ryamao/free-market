<?php

declare(strict_types=1);

namespace Tests\Browser\Items;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Items;
use Tests\DuskTestCase;

final class ShowTest extends DuskTestCase
{
    use DatabaseTruncation;

    private User $user;

    private Item $item;

    private Items\ShowPage $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $seller = User::factory()->create();
        $this->item = Item::factory()
            ->for($seller, 'seller')
            ->for(Condition::factory()->state(['name' => '良好']))
            ->create(['description' => 'サンプルテキスト']);

        foreach (['洋服', 'メンズ'] as $category) {
            $this->item->categories()->attach(
                Category::factory()->create(['name' => $category])
            );
        }

        $this->page = new Items\ShowPage($this->item);
    }

    #[Test]
    public function 商品詳細ページの要素を表示できる(): void
    {
        $this->browse(function ($browser) {
            $browser
                ->visit($this->page)
                ->assertPresent('@app-logo')
                ->assertPresent('@search-input')
                ->assertPresent('@item-image')
                ->assertSeeIn('main h2', $this->item->name)
                ->assertSeeIn('main', '¥'.number_format((float) $this->item->price))
                ->assertPresent('@favorite-button')
                ->assertPresent('main a[href="'.route('comments.index', $this->item).'"]')
                ->assertSeeIn('main', '購入する')
                ->assertSeeIn('main', '商品説明')
                ->assertSeeIn('main', 'サンプルテキスト')
                ->assertSeeIn('main', '商品の情報')
                ->assertSeeIn('main', 'カテゴリー')
                ->assertSeeIn('main a[href="'.route('home.search', ['q' => 'category:洋服']).'"]', '洋服')
                ->assertSeeIn('main a[href="'.route('home.search', ['q' => 'category:メンズ']).'"]', 'メンズ')
                ->assertSeeIn('main', '商品の状態')
                ->assertSeeIn('main a[href="'.route('home.search', ['q' => 'condition:良好']).'"]', '良好');
        });
    }

    #[Test]
    public function お気に入り登録ができる(): void
    {
        $this->browse(function ($browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->assertSeeIn('@favorite-count', '0')
                ->click('@favorite-button')
                ->waitForTextIn('@favorite-count', '1')
                ->click('@favorite-button')
                ->waitForTextIn('@favorite-count', '0')
                ->logout();
        });
    }

    #[Test]
    public function 商品詳細ページからコメントページに遷移できる(): void
    {
        $this->browse(function ($browser) {
            $browser
                ->visit($this->page)
                ->click('main a[href="'.route('comments.index', $this->item).'"]')
                ->waitForRoute('comments.index', ['item' => $this->item]);
        });
    }

    #[Test]
    public function ログイン中に商品詳細ページから商品購入ページに遷移できる(): void
    {
        $this->browse(function ($browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->clickLink('購入する')
                ->waitForRoute('purchase.create', ['item' => $this->item])
                ->logout();
        });
    }

    #[Test]
    public function 商品詳細ページからカテゴリーで検索できる(): void
    {
        $this->browse(function ($browser) {
            $browser
                ->visit($this->page)
                ->clickLink('洋服')
                ->waitForRoute('home.search')
                ->assertQueryStringHas('q', 'category:洋服');
        });
    }

    #[Test]
    public function 商品詳細ページから商品の状態で検索できる(): void
    {
        $this->browse(function ($browser) {
            $browser
                ->visit($this->page)
                ->clickLink('良好')
                ->waitForRoute('home.search')
                ->assertQueryStringHas('q', 'condition:良好');
        });
    }
}
