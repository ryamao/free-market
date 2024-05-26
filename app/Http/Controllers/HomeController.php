<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

final class HomeController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Items/Index', [
            'routeName' => 'home.index',
        ]);
    }

    public function search(Request $request): \Inertia\Response
    {
        $searchString = $request->input('q');
        if (! is_string($searchString)) {
            $searchString = '';
        }

        return Inertia::render('Items/Index', [
            'routeName' => 'home.search',
            'searchString' => $searchString,
        ]);
    }

    public function mylist(): \Inertia\Response
    {
        return Inertia::render('Items/Index', [
            'routeName' => 'home.mylist',
        ]);
    }
}
