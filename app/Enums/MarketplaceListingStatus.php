<?php

namespace App\Enums;

enum MarketplaceListingStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Rejected = 'rejected';
    case Sold = 'sold';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ duyệt',
            self::Active => 'Đang hiển thị',
            self::Rejected => 'Bị từ chối',
            self::Sold => 'Đã bán',
        };
    }
}
