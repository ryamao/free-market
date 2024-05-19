<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $image_url
 * @property string $description
 * @property int $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \App\Models\User $seller
 * @property \App\Models\Condition $condition
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Category> $categories
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\User> $watchers
 */
final class Item extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['user_id', 'condition_id', 'name', 'image_url', 'description', 'price'];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, self> */
    public function seller(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Condition, self> */
    public function condition(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Category> */
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'item_category')->using(ItemCategory::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User> */
    public function watchers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
