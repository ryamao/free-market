<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateNewItem;
use App\Http\Requests\ItemsStoreRequest;
use App\Models\Condition;
use Inertia\Inertia;

final class SaleController extends Controller
{
    public function create(): \Inertia\Response
    {
        $conditions = Condition::all()->pluck('name');

        return Inertia::render('Items/Create', [
            'conditions' => $conditions,
        ]);
    }

    public function store(ItemsStoreRequest $request, CreateNewItem $action): \Illuminate\Http\RedirectResponse
    {
        $action($request);

        return redirect()->route('dashboard');
    }
}
