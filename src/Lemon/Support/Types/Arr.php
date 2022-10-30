<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

/**
 * Here is regex for replacing Arr::hasKey to array_key_exists
 * :%s/Arr::hasKey(\(.\+\),\s*\([^)]\+\))/array_key_exists(\2, \1).
 */
class Arr
{
    public static function last(array $array): mixed
    {
        $key = array_key_last($array);

        return is_null($key) ? $key : $array[$key];
    }

    public static function first(array $array): mixed
    {
        $key = array_key_first($array);

        return is_null($key) ? $key : $array[$key];
    }
}
