<?php

declare(strict_types=1);

namespace CruxUI\Statamic\Enums;

use Statamic\Statamic;

enum Stack: string
{
    case Blade = 'blade';
    case Antlers = 'antlers';

    public static function detect(): self
    {
        return class_exists(Statamic::class) ? self::Antlers : self::Blade;
    }
}
