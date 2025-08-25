<?php

namespace App\Enums;

enum RoomStatus: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case MAINTENANCE = 'Maintenance';
    case OCCUPIED = 'Occupied';
    case OTHER = 'Other';
}
