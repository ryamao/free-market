<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class UsersShowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function ユーザーページを表示できる(): void
    {
        $response = $this->get(route('users.show', $this->user));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('User/Show')
            ->has('user', fn (AssertableInertia $page) => $page
                ->has('data', fn (AssertableInertia $page) => $page
                    ->where('id', $this->user->id)
                    ->etc()
                )
            )
        );
    }
}
