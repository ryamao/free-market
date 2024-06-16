<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DirectMailsCreateTest extends TestCase
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
    public function 管理者がメール送信ページを表示できる(): void
    {
        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('direct-mails.create', $this->user));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Email')
            ->has('user', fn (AssertableInertia $page) => $page
                ->has('data', fn (AssertableInertia $page) => $page
                    ->where('id', $this->user->id)
                    ->etc()
                )
            )
        );
    }
}
