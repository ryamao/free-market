<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\ProfileEditPage;
use Tests\DuskTestCase;

final class ProfileEditTest extends DuskTestCase
{
    use DatabaseTruncation;

    private User $user;

    private ProfileEditPage $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->page = new ProfileEditPage();
    }

    #[Test]
    public function プロフィール編集ページの要素が表示される(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit($this->page)
                ->assertSeeIn('@title', 'プロフィール設定')
                ->assertSeeIn('button', '画像を選択する')
                ->assertSeeIn('@name-label', 'ユーザー名')
                ->assertSeeIn('@postcode-label', '郵便番号')
                ->assertSeeIn('@address-label', '住所')
                ->assertSeeIn('@building-label', '建物名')
                ->assertInputValue('@name-input', $this->user->name ?? '')
                ->assertInputValue('@postcode-input', $this->user->postcode ?? '')
                ->assertInputValue('@address-input', $this->user->address ?? '')
                ->assertInputValue('@building-input', $this->user->building ?? '')
                ->assertSeeIn('button[type="submit"]', '更新する');
        });
    }
}
