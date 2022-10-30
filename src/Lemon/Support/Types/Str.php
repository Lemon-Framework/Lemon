<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

/**
 * Regex for handling deprecation of self::replace
 * :%s/\((string)\s*\)\?Str::replace(\([^,]\+\),\s*\([^,]\+\),\s*\(.\+\))\(->value\)\?/str_replace(\3, \4, \2).
 */
class Str
{
    public static function random(int $size): string
    {
        $chars = str_split(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));

        return array_reduce(array_rand($chars, $size), fn ($carry, $item) => $carry.$chars[$item]);
    }
}
