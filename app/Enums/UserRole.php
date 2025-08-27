<?php

namespace App\Enums;

enum UserRole
{
    case USER;
    case ADMIN;
    case CLUB_ADMIN;
    case CLUB_MEMBER;
    case CLUB_OWNER;
    case CLUB_MODERATOR;
    case CLUB_EDITOR;
    case CLUB_VIEWER;
}
