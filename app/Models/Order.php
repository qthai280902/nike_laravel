<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'payment_method',
        'inventory_returned_at',
        'inventory_returned_by_user_id',
        'inventory_return_note',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'inventory_returned_at' => 'datetime',
        ];
    }

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the admin who returned inventory for a cancelled order.
     */
    public function inventoryReturnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inventory_returned_by_user_id');
    }
}
