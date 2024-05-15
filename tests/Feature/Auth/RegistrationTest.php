<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);
    }

    #[Test]
    public function 会員登録画面が表示される(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    #[Test]
    public function 新規ユーザーが登録できる(): void
    {
        $response = $this->from('/register')->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/mypage/profile');
    }

    /**
     * @param  array<string, string>  $params
     * @param  array<string, string>  $messages
     */
    #[Test]
    #[TestWith(
        [
            ['email' => '', 'password' => 'password'],
            ['email' => 'メールアドレスを入力してください'],
        ],
        'メールアドレスが未入力の場合'
    )]
    #[TestWith(
        [
            ['email' => 'invalid-email', 'password' => 'password'],
            ['email' => 'メールアドレスの形式が間違っています'],
        ],
        'メールアドレスが不正な形式の場合'
    )]
    #[TestWith(
        [
            ['email' => 'test@example.com', 'password' => ''],
            ['password' => 'パスワードを入力してください'],
        ],
        'パスワードが未入力の場合'
    )]
    #[TestWith(
        [
            ['email' => 'test@example.com', 'password' => 'passwor'],
            ['password' => 'パスワードは8文字以上で入力してください'],
        ],
        'パスワードが8文字未満の場合'
    )]
    public function バリデーションエラーを返す($params, $messages): void
    {
        $response = $this->from('/register')->post('/register', $params);

        $response->assertSessionHasErrors($messages);
        $response->assertRedirect('/register');
    }

    #[Test]
    public function メールアドレスがすでに登録されている(): void
    {
        $response = $this->from('/register')->post('/register', [
            'email' => $this->user->email,
            'password' => 'password2',
        ]);

        $response->assertSessionHasErrors([
            'email' => '同じメールアドレスがすでに登録されています',
        ]);
        $response->assertRedirect('/register');
    }

    #[Test]
    public function ログイン中に会員登録ページにアクセスするとマイページにリダイレクトされる(): void
    {
        $response = $this->actingAs($this->user)->get('/register');

        $response->assertRedirect('/mypage');
    }
}
