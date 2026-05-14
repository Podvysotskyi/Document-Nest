<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Archived = 'archived';
}
