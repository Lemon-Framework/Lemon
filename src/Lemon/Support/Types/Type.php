<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

class Type
{
    public static function is(string $type, mixed $value): bool
    {
        return
            'mixed' === $type
            || gettype($value) === $type
            || $value instanceof $type
        ;
    }
}
