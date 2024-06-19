<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
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
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Index')
            ->has('users', fn (AssertableInertia $page) => $page
                ->has('data', 1)
                ->has('data.0', fn (AssertableInertia $page) => $page
                    ->where('id', $this->user->id)
                    ->etc()
                )
            )
        );
    }

    #[Test]
    public function 削除済みのユーザーは表示されない(): void
    {
        $this->user->delete();

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Index')
            ->has('users', fn (AssertableInertia $page) => $page
                ->has('data', 0)
            )
        );
    }

    #[Test]
    public function 管理者以外は管理ページにアクセスできない(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('admin.index'));

        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function ログインしていない場合は管理ページにアクセスできない(): void
    {
        $response = $this->get(route('admin.index'));

        $response->assertRedirect(route('admin.login'));
    }
}
