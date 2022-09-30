<?php

declare(strict_types=1);

namespace Lemon\Database\Tree\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AutoIncrement
{
    public static function compile(string $driver, string $type): string
    {
        return match ($driver) {
            'postgre' => 'SERIAL',
             default => $type.'AUTOINCREMENT',
        };
    }
}
