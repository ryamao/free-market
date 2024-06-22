<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class HomeMylistTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function ログインユーザーはお気に入り一覧ページを表示できる(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('home.mylist'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->where('routeName', 'home.mylist')
        );
    }

    #[Test]
    public function ゲストユーザーはお気に入り一覧ページを表示できない(): void
    {
        $response = $this->get(route('home.mylist'));

        $response->assertRedirect(route('login'));
    }
}
