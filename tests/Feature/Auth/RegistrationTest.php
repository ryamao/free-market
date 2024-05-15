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
            ['email' => 'Eメールは必須項目です。'],
        ],
        'メールアドレスが未入力の場合'
    )]
    #[TestWith(
        [
            ['email' => 'invalid-email', 'password' => 'password'],
            ['email' => 'Eメールは、有効なメールアドレス形式で指定してください。'],
        ],
        'メールアドレスが不正な形式の場合'
    )]
    #[TestWith(
        [
            ['email' => 'test@example.com', 'password' => ''],
            ['password' => 'パスワードは必須項目です。'],
        ],
        'パスワードが未入力の場合'
    )]
    #[TestWith(
        [
            ['email' => 'test@example.com', 'password' => 'passwor'],
            ['password' => 'パスワードの文字数は、8文字以上である必要があります。'],
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
            'email' => '指定のeメールは既に使用されています。',
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
