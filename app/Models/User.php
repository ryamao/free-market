<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

/**
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property string|null $image_url
 * @property string|null $postcode
 * @property string|null $prefecture
 * @property string|null $address
 * @property string|null $building
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\Collection<\App\Models\Item> $items
 * @property-read \Illuminate\Support\Collection<\App\Models\Favorite> $favorites
 * @property-read \Illuminate\Support\Collection<\App\Models\Purchase> $purchases
 * @property-read \Illuminate\Support\Collection<\App\Models\Item> $purchasedItems
 */
final class User extends Authenticatable
{
    use Billable, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'name',
        'image_url',
        'postcode',
        'prefecture',
        'address',
        'building',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Item> */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Item> */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'favorites');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Purchase> */
    public function purchases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Item> */
    public function purchasedItems(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'purchases')->whereNotNull('paid_at');
    }
}
