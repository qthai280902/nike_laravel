<?php

namespace App\Enums;

enum MarketplaceListingCondition: string
{
    case NewWithBox = 'new_with_box';
    case LikeNew = 'like_new';
    case Good = 'good';
    case Fair = 'fair';

    public function label(): string
    {
        return match ($this) {
            self::NewWithBox => 'Mới nguyên hộp',
            self::LikeNew => 'Như mới',
            self::Good => 'Tốt',
            self::Fair => 'Đã qua sử dụng',
        };
    }
}
