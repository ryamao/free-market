<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
    }

    #[Test]
    public function ログインページを表示できる(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Login')
        );
    }

    #[Test]
    public function ログイン中は管理ページにリダイレクトされる(): void
    {
        $this->actingAs($this->admin, 'admin');
        $this->fromRoute('admin.index');
        $response = $this->get(route('admin.login'));

        $response->assertRedirectToRoute('admin.index');
    }
}
