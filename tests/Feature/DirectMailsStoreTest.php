<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\DirectMail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DirectMailsStoreTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function 管理者はユーザーにダイレクトメールを送信できる(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');
        $response = $this->post(route('direct-mails.store', $this->user), [
            'title' => 'タイトル',
            'content' => '本文',
        ]);

        $response->assertStatus(201);
        Mail::assertSent(DirectMail::class, function (DirectMail $mail) {
            return $mail->hasTo($this->user->email)
                && $mail->hasSubject('タイトル');
        });
    }

    #[Test]
    public function 必須項目が入力されていない場合はエラーを返す(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');
        $response = $this->postJson(route('direct-mails.store', $this->user), [
            'title' => '',
            'content' => '',
        ]);

        $response->assertStatus(422);
        $response->assertInvalid(['title', 'content']);
        Mail::assertNothingSent();
    }
}
