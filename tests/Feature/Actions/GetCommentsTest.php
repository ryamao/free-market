<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\GetComments;
use App\Models\Item;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class GetCommentsTest extends TestCase
{
    use RefreshDatabase;

    private GetComments $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(GetComments::class);
    }

    #[Test]
    public function 商品のコメントを取得できる(): void
    {
        $this->seed(TestDataSeeder::class);

        $item = Item::firstOrFail();
        $expected = $item->comments->sortBy('created_at')->values();
        $actual = ($this->action)($item);

        $this->assertEquals($expected->pluck('id'), collect($actual)->pluck('id'));
    }
}
