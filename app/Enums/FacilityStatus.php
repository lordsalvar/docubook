<?php

namespace App\Enums;

enum FacilityStatus: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case UNDER_MAINTENANCE = 'Under maintenance';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::UNDER_MAINTENANCE => 'Under Maintenance',
        };
    }
}
