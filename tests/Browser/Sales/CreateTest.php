<?php

declare(strict_types=1);

namespace Tests\Browser\Items;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Items\CreatePage;
use Tests\DuskTestCase;

final class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    private CreatePage $page;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = new CreatePage();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function 出品ページの要素にアクセスできる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->assertSeeIn('@title', '商品の出品')
                ->assertSee('商品の詳細')
                ->assertSee('商品名と説明')
                ->assertSee('販売価格')
                ->assertSee('商品画像')
                ->assertSeeIn('@categories-label', 'カテゴリー')
                ->assertSeeIn('@condition-label', '商品の状態')
                ->assertSeeIn('@name-label', '商品名')
                ->assertSeeIn('@description-label', '商品の説明')
                ->assertSeeIn('@price-label', '販売価格')
                ->assertPresent('@categories-input')
                ->assertPresent('@condition-select')
                ->assertPresent('@name-input')
                ->assertPresent('@description-textarea')
                ->assertPresent('@price-input')
                ->assertSeeIn('button', '画像を選択する')
                ->assertSeeIn('@submit-button', '出品する')
                ->logout();
        });
    }
}
