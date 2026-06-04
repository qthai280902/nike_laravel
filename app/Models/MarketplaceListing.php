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
        'product_name',
        'brand',
        'size',
        'color',
        'image_url',
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
     * Display name for both catalog-linked and freeform listings.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->product_name
            ?: $this->variant?->product?->name
            ?: 'Sản phẩm chưa có tên';
    }

    /**
     * Display brand for both catalog-linked and freeform listings.
     */
    public function getDisplayBrandAttribute(): string
    {
        return $this->brand ?: 'Nike';
    }

    /**
     * Display image with a safe local fallback.
     */
    public function getDisplayImageUrlAttribute(): string
    {
        $imageUrl = $this->image_url ?: $this->variant?->product?->image_url;

        if (empty($imageUrl)) {
            return Product::fallbackImageUrl();
        }

        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
            return $imageUrl;
        }

        return asset(ltrim($imageUrl, '/'));
    }

    /**
     * Display size for both catalog-linked and freeform listings.
     */
    public function getDisplaySizeAttribute(): string
    {
        return $this->size ?: $this->variant?->size ?: 'Chưa rõ';
    }

    /**
     * Display color for both catalog-linked and freeform listings.
     */
    public function getDisplayColorAttribute(): string
    {
        return $this->color ?: $this->variant?->color ?: 'Chưa rõ';
    }

    /**
     * Display source type.
     */
    public function getDisplaySourceAttribute(): string
    {
        return $this->product_variant_id ? 'Catalog cửa hàng' : 'Tin đăng tự nhập';
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
