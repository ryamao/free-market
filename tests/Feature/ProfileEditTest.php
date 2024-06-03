<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'image_url' => 'https://example.com/image.jpg',
        ]);
    }

    #[Test]
    public function プロフィール編集ページが表示される(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('profile.edit'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Profile/Edit')
            ->has('user', fn (AssertableInertia $page) => $page
                ->has('data', fn (AssertableInertia $page) => $page
                    ->where('id', $this->user->id)
                    ->where('name', $this->user->name)
                    ->where('image_url', $this->user->image_url)
                )
            )
            ->has('profile', fn (AssertableInertia $page) => $page
                ->has('data', fn (AssertableInertia $page) => $page
                    ->where('postcode', $this->user->postcode)
                    ->where('prefecture', $this->user->prefecture)
                    ->where('address', $this->user->address)
                    ->where('building', $this->user->building)
                )
            )
        );
    }
}
