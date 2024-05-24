<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ItemsStoreTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create();
        Category::factory()->create(['name' => 'カテゴリA']);
        Condition::factory()->create(['name' => '未使用']);
    }

    #[Test]
    public function 商品を出品できる(): void
    {
        $file = UploadedFile::fake()->image('item.jpg');

        $response = $this->actingAs($this->user)
            ->fromRoute('items.create')
            ->post(route('items.store'), [
                'name' => '商品名',
                'image' => $file,
                'price' => 1000,
                'description' => '商品の説明',
                'categories' => 'カテゴリA カテゴリB',
                'condition' => '未使用',
            ]);

        $response->assertRedirectToRoute('dashboard');

        $item = Item::where('name', '商品名')->firstOrFail();

        $this->assertTrue(Storage::disk('public')->exists('item_images/'.$item->id.'.jpg'));
    }
}
