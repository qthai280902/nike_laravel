<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    use HasUuids;

    public const FALLBACK_IMAGE_PATH = 'images/hero.png';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'product_story',
        'highlights',
        'care_instructions',
        'price',
        'original_price',
        'image_url',
        'featured_position',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'highlights' => 'array',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get approved storefront reviews for the product.
     */
    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    /**
     * Get the product image url with fallback.
     */
    public function getImageUrlAttribute(?string $value): string
    {
        if ($this->hasUnsafeImageUrl($value)) {
            return self::fallbackImageUrl();
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    public static function fallbackImageUrl(): string
    {
        return asset(self::FALLBACK_IMAGE_PATH);
    }

    private function hasUnsafeImageUrl(?string $value): bool
    {
        if (empty($value)) {
            return true;
        }

        $normalizedValue = strtolower($value);

        return str_contains($normalizedValue, 'images.unsplash.com')
            || str_contains($normalizedValue, 'placeholder')
            || str_contains($normalizedValue, 'images.nike.com');
    }
}
