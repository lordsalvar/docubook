<?php

namespace App\Enums;

enum ClubType: string
{
    case ACADEMIC = 'Academic';
    case NON_ACADEMIC = 'Non-Academic';
    case CLGU = 'CLGU';
    case OTHER = 'Other';
}
