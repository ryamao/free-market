<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property string $postcode
 * @property string $address
 * @property string|null $building
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\User $user
 */
final class Purchase extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'item_id',
        'user_id',
        'postcode',
        'address',
        'building',
    ];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Item, self> */
    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, self> */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
