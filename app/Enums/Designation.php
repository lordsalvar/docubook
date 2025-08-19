<?php

namespace App\Enums;

enum Designation: string
{
    case DEAN = 'Dean';
    case ADMIN = 'Admin';
    case PRESIDENT = 'President';
    case VICE_PRESIDENT = 'Vice President';
    case SECRETARY = 'Secretary';
    case TREASURER = 'Treasurer';
    case MEMBER = 'Member';
}
