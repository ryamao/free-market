<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class SalesCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function 出品ページにアクセスできる(): void
    {
        $conditions = Condition::factory(5)
            ->sequence(fn (Sequence $seq) => ['name' => 'condition '.$seq->index])
            ->create();

        $this->actingAs($this->user);
        $response = $this->get(route('sales.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Create')
            ->where('conditions', $conditions->pluck('name')->toArray())
        );
    }
}
