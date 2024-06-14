<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class AttemptTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
    }

    #[Test]
    public function 管理者としてログインできる(): void
    {
        $response = $this->post(route('admin.attempt'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.index'));
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    #[Test]
    public function メールアドレスが存在しない場合はログインできない(): void
    {
        $response = $this->post(route('admin.attempt'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    #[Test]
    public function 一般ユーザーはログインできない(): void
    {
        $response = $this->post(route('admin.attempt'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }
}
