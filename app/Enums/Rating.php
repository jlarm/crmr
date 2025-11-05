<?php

declare(strict_types=1);

namespace App\Enums;

enum Rating: string
{
    case HOT = 'hot';
    case WARM = 'warm';
    case COLD = 'cold';

    public function label(): string
    {
        return match ($this) {
            self::HOT => 'Hot',
            self::WARM => 'Warm',
            self::COLD => 'Cold',
        };
    }
}
