<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetLatestItems;
use Inertia\Inertia;

final class ItemController extends Controller
{
    public function index(): \Inertia\Response
    {
        $items = app(GetLatestItems::class)();

        return Inertia::render('Items/Index', [
            'items' => $items,
        ]);
    }

    public function search(): \Inertia\Response
    {
        return Inertia::render('Items/Index');
    }

    public function mylist(): \Inertia\Response
    {
        return Inertia::render('Items/Index');
    }
}
