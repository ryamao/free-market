<?php

declare(strict_types=1);

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class HomeIndexTest extends TestCase
{
    #[Test]
    public function トップページに商品一覧を表示する(): void
    {
        $response = $this->get(route('home.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->where('routeName', 'home.index')
        );
    }
}
