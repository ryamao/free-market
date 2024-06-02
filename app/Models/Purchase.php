<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property string $payment_intent_id
 * @property \Illuminate\Support\Carbon|null $paid_at
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
        'payment_intent_id',
        'paid_at',
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

    public function payment(): ?\Laravel\Cashier\Payment
    {
        return $this->user->findPayment($this->payment_intent_id);
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function markAsPaid(): void
    {
        $this->update(['paid_at' => now()]);
    }
}
