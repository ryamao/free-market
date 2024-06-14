<?php

declare(strict_types=1);

namespace Tests\Browser\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Admin\IndexPage;
use Tests\DuskTestCase;

final class IndexTest extends DuskTestCase
{
    use DatabaseTruncation;

    private IndexPage $page;

    private Admin $admin;

    /** @var Collection<int, User> */
    private Collection $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = new IndexPage();
        $this->admin = Admin::factory()->create();
        $this->users = User::factory(3)->create();
    }

    #[Test]
    public function 管理者は登録ユーザー一覧を閲覧できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin');
            $browser->visit($this->page);

            foreach ($this->users as $i => $user) {
                $browser->assertSeeIn('main ul li:nth-of-type('.($i + 1).')', $user->name ?? '');
            }
        });
    }
}
