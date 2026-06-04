<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'admin_note',
        'resolved_at',
        'resolved_by_user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the support ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin user that resolved or closed the support ticket.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }

    /**
     * Get the Vietnamese label for the ticket status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'Mới gửi',
            'in_progress' => 'Đang xử lý',
            'resolved' => 'Đã xử lý',
            'closed' => 'Đã đóng',
            default => $this->status,
        };
    }
}
