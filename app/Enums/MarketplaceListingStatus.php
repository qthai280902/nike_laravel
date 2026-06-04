<?php

namespace App\Enums;

enum MarketplaceListingStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Rejected = 'rejected';
    case Sold = 'sold';
    case Hidden = 'hidden';
    case Deleted = 'deleted';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ duyệt',
            self::Active => 'Đang hiển thị',
            self::Rejected => 'Bị từ chối',
            self::Sold => 'Đã bán',
            self::Hidden => 'Đã ẩn',
            self::Deleted => 'Đã xóa',
        };
    }
}
