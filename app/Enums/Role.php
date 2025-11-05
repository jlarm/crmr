<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case CONSULTANT = 'consultant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::CONSULTANT => 'Consultant',
        };
    }
}
