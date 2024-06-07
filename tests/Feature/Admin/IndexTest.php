<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class IndexTest extends TestCase
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
    public function 管理ページにアクセスできる(): void
    {
        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function 管理者以外は管理ページにアクセスできない(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('admin.index'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function ログインしていない場合は管理ページにアクセスできない(): void
    {
        $response = $this->get(route('admin.index'));

        $response->assertRedirect(route('login'));
    }
}
