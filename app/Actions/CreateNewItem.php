<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\ItemsStoreRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

final class CreateNewItem
{
    public function __invoke(ItemsStoreRequest $request, User $user): void
    {
        $condition = Condition::where('name', $request->condition)->firstOrFail();

        $item = $user->items()->create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'condition_id' => $condition->id,
            'image_url' => '',
        ]);

        $this->createCategories($item, $request->categories);
        $this->saveImage($item, $request->image);
    }

    private function createCategories(Item $item, string $words): void
    {
        $names = preg_split('/\s+/', $words);
        if (! $names) {
            return;
        }

        $categories = collect($names)->map(function (string $name) {
            return Category::firstOrCreate(['name' => $name], []);
        });
        foreach ($categories as $category) {
            $item->categories()->attach($category);
        }
    }

    private function saveImage(Item $item, UploadedFile $image): void
    {
        $imageName = $item->id.'.'.$image->extension();
        $imagePath = $image->storeAs('item_images', $imageName);
        if (! $imagePath) {
            throw ValidationException::withMessages(['image' => '画像の保存に失敗しました。']);
        }
        $item->image_url = Storage::url($imagePath);
        $item->save();
    }
}
