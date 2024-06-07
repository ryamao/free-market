<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\AddComment;
use App\Actions\GetComments;
use App\Http\Requests\CommentsStoreRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Inertia\Inertia;

final class CommentController extends Controller
{
    public function index(Item $item, GetComments $action): \Inertia\Response
    {
        $comments = $action($item);

        return Inertia::render('Comments/Index', [
            'item' => ItemResource::make($item),
            'comments' => $comments,
        ]);
    }

    public function store(CommentsStoreRequest $request, Item $item, AddComment $action): \Illuminate\Http\Response
    {
        assert($request->user() instanceof \App\Models\User);

        $action($request->user(), $item, $request->content);

        return response()->noContent();
    }
}
