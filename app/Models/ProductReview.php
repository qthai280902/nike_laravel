<?php

namespace App\Models;

use Database\Factories\ProductReviewFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    /** @use HasFactory<ProductReviewFactory> */
    use HasFactory, HasUuids;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_HIDDEN = 'hidden';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'product_id',
        'user_id',
        'author_name',
        'rating',
        'title',
        'comment',
        'status',
        'rejection_reason',
        'moderated_at',
        'moderated_by_user_id',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'moderated_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by_user_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeHidden(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_HIDDEN);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Đang chờ duyệt',
            self::STATUS_APPROVED => 'Đã duyệt',
            self::STATUS_HIDDEN => 'Đang ẩn',
            self::STATUS_REJECTED => 'Đã từ chối',
            default => (string) $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'bg-emerald-500/15 text-emerald-600',
            self::STATUS_HIDDEN => 'bg-zinc-500/15 text-zinc-600',
            self::STATUS_REJECTED => 'bg-red-500/15 text-red-600',
            default => 'bg-yellow-500/15 text-yellow-700',
        };
    }

    public function getIsPublicAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
