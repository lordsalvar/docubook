<?php

namespace App\Enums;

enum OrgType: string
{
    case ACADEMIC = 'Academic';
    case NON_ACADEMIC = 'Non-Academic';
    case CLGU = 'CLGU';
    case OTHER = 'Other';
}
