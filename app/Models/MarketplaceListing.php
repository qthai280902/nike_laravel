<?php

namespace App\Models;

use App\Enums\MarketplaceListingCondition;
use App\Enums\MarketplaceListingStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketplaceListing extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_variant_id',
        'asking_price',
        'condition',
        'seller_description',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'asking_price' => 'decimal:2',
            'condition' => MarketplaceListingCondition::class,
            'status' => MarketplaceListingStatus::class,
        ];
    }

    /**
     * Scope a query to only include active listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', MarketplaceListingStatus::Active->value);
    }

    /**
     * Scope a query to only include pending listings.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', MarketplaceListingStatus::Pending->value);
    }

    /**
     * Get the user that owns the listing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specific product variant being sold.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Accessor for condition label.
     */
    public function getConditionLabelAttribute(): string
    {
        return $this->condition instanceof MarketplaceListingCondition
            ? $this->condition->label()
            : MarketplaceListingCondition::tryFrom((string) $this->condition)?->label() ?? 'Không xác định';
    }

    /**
     * Accessor for status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status instanceof MarketplaceListingStatus
            ? $this->status->label()
            : MarketplaceListingStatus::tryFrom((string) $this->status)?->label() ?? 'Không xác định';
    }
}
