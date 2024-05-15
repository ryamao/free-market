<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

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
    public function ログインページが表示される(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    #[Test]
    public function ログインページでユーザーが認証できる(): void
    {
        $response = $this->fromRoute('login')->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirectToRoute('dashboard');
    }

    #[Test]
    public function ログインページで項目が入力されていない場合は認証できない(): void
    {
        $response = $this->fromRoute('login')->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Eメールは必須項目です。',
            'password' => 'パスワードは必須項目です。',
        ]);
    }

    #[Test]
    public function ログインページでパスワードが間違っている場合は認証できない(): void
    {
        $response = $this->fromRoute('login')->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => '認証に失敗しました。']);
    }

    #[Test]
    public function ログアウトできる(): void
    {
        $response = $this->actingAs($this->user)->fromRoute('home')->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirectToRoute('home');
    }
}
