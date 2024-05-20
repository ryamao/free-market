<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Item
 */
final class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'seller' => UserResource::make($this->seller),
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'price' => $this->price,
            'condition' => $this->condition->name,
            'categories' => $this->categories->pluck('name'),
            'favorite_count' => $this->watchers->count(),
            'is_favorite' => $request->user() ? $this->watchers->contains($request->user()) : false,
            'comment_count' => $this->comments->count(),
            'created_at' => $this->created_at,
        ];
    }
}
