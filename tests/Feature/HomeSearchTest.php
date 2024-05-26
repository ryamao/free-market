<?php

declare(strict_types=1);

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class HomeSearchTest extends TestCase
{
    #[Test]
    public function 検索キーワードを指定して検索結果を表示する(): void
    {
        $response = $this->get(route('home.search', ['q' => 'テスト']));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->where('routeName', 'home.search')
            ->where('searchString', 'テスト')
        );
    }

    #[Test]
    public function 検索キーワードなしで検索結果を表示する(): void
    {
        $response = $this->get(route('home.search'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Items/Index')
            ->where('routeName', 'home.search')
            ->where('searchString', '')
        );
    }
}
