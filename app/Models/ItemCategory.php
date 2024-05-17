<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $item_id
 * @property int $category_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
final class ItemCategory extends Pivot
{
    /** @var string */
    protected $table = 'item_category';

    /** @var list<string> */
    protected $fillable = ['item_id', 'category_id'];
}
