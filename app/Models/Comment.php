<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $content
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\User $user
 */
final class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /** @var list<string> */
    protected $fillable = ['user_id', 'item_id', 'content'];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Item, self> */
    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self> */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
