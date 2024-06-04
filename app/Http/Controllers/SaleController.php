<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateNewItem;
use App\Actions\GetItemsBySeller;
use App\Http\Requests\ItemsStoreRequest;
use App\Models\Condition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;

final class SaleController extends Controller
{
    public function index(Request $request, GetItemsBySeller $action): AnonymousResourceCollection
    {
        assert($request->user() !== null);

        return $action($request->user());
    }

    public function create(): \Inertia\Response
    {
        $conditions = Condition::all()->pluck('name');

        return Inertia::render('Items/Create', [
            'conditions' => $conditions,
        ]);
    }

    public function store(ItemsStoreRequest $request, CreateNewItem $action): RedirectResponse
    {
        $action($request);

        return redirect()->route('dashboard');
    }
}
