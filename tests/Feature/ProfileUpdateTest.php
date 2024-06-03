<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function プロフィールを更新できる(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'name' => 'updated name',
                'image' => UploadedFile::fake()->image('image.jpg'),
                'postcode' => '1234567',
                'prefecture' => 'updated prefecture',
                'address' => 'updated address',
                'building' => 'updated building name',
            ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'updated name',
            'postcode' => '1234567',
            'prefecture' => 'updated prefecture',
            'address' => 'updated address',
            'building' => 'updated building name',
        ]);
    }
}
