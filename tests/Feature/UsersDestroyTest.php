<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class UsersDestroyTest extends TestCase
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
    public function ユーザーを削除できる(): void
    {
        $this->actingAs($this->admin, 'admin');
        $response = $this->delete(route('users.destroy', $this->user));

        $response->assertStatus(204);
        $this->assertTrue($this->user->fresh()?->isDeleted());
    }

    #[Test]
    public function 管理者以外はユーザーを削除できない(): void
    {
        $this->actingAs($this->user);
        $response = $this->delete(route('users.destroy', $this->user));

        $response->assertRedirectToRoute('login');
        $this->assertFalse($this->user->fresh()?->isDeleted());
    }

    #[Test]
    public function 削除済みのユーザーは削除できない(): void
    {
        $this->user->markAsDeleted();

        $this->actingAs($this->admin, 'admin');
        $response = $this->delete(route('users.destroy', $this->user));

        $response->assertStatus(409);
    }
}
