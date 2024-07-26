<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\ItemResource;
use App\Models\Item;

final class SearchItems
{
    public function __invoke(?string $searchString): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $builder = Item::with(['seller', 'condition', 'categories', 'watchers', 'comments']);
        $builder
            ->whereHas('seller')
            ->whereNull('sold_at');

        $parseResult = self::parseSearchString($searchString);
        foreach ($parseResult['keywords'] as $keyword) {
            $builder->where(fn ($query) => $query
                ->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
            );
        }
        foreach ($parseResult['conditions'] as $condition) {
            $builder->whereHas('condition', fn ($query) => $query->where('name', $condition));
        }
        foreach ($parseResult['categories'] as $category) {
            $builder->whereHas('categories', fn ($query) => $query->where('name', $category));
        }

        $items = $builder
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->paginate(10);

        return ItemResource::collection($items);
    }

    /** @return array{keywords: list<string>, conditions: list<string>, categories: list<string>} */
    private static function parseSearchString(?string $string): array
    {
        $keywords = [];
        $conditions = [];
        $categories = [];

        if (is_null($string) || empty($string)) {
            return compact('keywords', 'conditions', 'categories');
        }

        $strings = preg_split('/\s+/', addcslashes($string, '%_')) ?: [];
        foreach ($strings as $string) {
            if (str_starts_with($string, 'condition:')) {
                $condition = mb_substr($string, mb_strlen('condition:'));
                if (! empty($condition)) {
                    $conditions[] = $condition;
                }
            } elseif (str_starts_with($string, 'category:')) {
                $category = mb_substr($string, mb_strlen('category:'));
                if (! empty($category)) {
                    $categories[] = $category;
                }
            } else {
                $keywords[] = $string;
            }
        }

        return compact('keywords', 'conditions', 'categories');
    }
}
