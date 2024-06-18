<?php

declare(strict_types=1);

namespace Tests\Browser\Commens;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\Commens\IndexPage;
use Tests\DuskTestCase;

final class IndexTest extends DuskTestCase
{
    use DatabaseTruncation;

    private Admin $admin;

    private User $seller;

    private User $user;

    private Item $item;

    private Comment $comment1;

    private Comment $comment2;

    private IndexPage $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();

        $this->seller = User::factory()->create();
        $this->item = Item::factory()->for($this->seller, 'seller')->create();

        $this->user = User::factory()->create();
        $this->comment1 = Comment::create([
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'content' => 'コメント1',
        ]);
        $this->comment2 = Comment::create([
            'item_id' => $this->item->id,
            'user_id' => $this->seller->id,
            'content' => 'コメント2',
        ]);

        $this->page = new IndexPage($this->item);
    }

    #[Test]
    public function コメント一覧を閲覧できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->page);
            $browser->assertSeeIn('@comment-list li:nth-of-type(1)', $this->comment1->user->name ?? '(名前未設定)');
            $browser->assertSeeIn('@comment-list li:nth-of-type(1)', $this->comment1->content);
            $browser->assertDontSeeIn('@comment-list li:nth-of-type(1)', '(出品者)');
            $browser->assertSeeIn('@comment-list li:nth-of-type(2)', $this->comment2->user->name ?? '(名前未設定)');
            $browser->assertSeeIn('@comment-list li:nth-of-type(2)', $this->comment2->content);
            $browser->assertSeeIn('@comment-list li:nth-of-type(2)', '(出品者)');
        });
    }

    #[Test]
    public function ゲストはコメントを投稿できない(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->page);
            $browser->assertButtonDisabled('コメントを送信する');
        });
    }

    #[Test]
    public function ログインユーザーはコメントを投稿できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user);
            $browser->visit($this->page);
            $browser->type('@content-input', 'コメント3');
            $browser->press('コメントを送信する');
            $browser->waitForTextIn('@comment-list li:nth-of-type(3)', 'コメント3');
        });
    }

    #[Test]
    public function ゲストはコメントを削除できない(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->page);
            $browser->assertMissing('@comment-list li:nth-of-type(1) button');
        });
    }

    #[Test]
    public function 一般ユーザーはコメントを削除できない(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user);
            $browser->visit($this->page);
            $browser->assertMissing('@comment-list li:nth-of-type(1) button');
        });
    }

    #[Test]
    public function 管理者はコメントを削除できる(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin');
            $browser->visit($this->page);
            $browser->press('@comment-list li:nth-of-type(1) button');
            $browser->waitForDialog();
            $browser->acceptDialog();
            $browser->waitForTextIn('@comment-list li:nth-of-type(1)', $this->comment2->content);
            $browser->assertMissing('@comment-list li:nth-of-type(2)');
        });
    }
}
