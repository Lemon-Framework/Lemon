<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

use Generator;

/**
 * Here is regex for replacing Arr::hasKey to array_key_exists
 * :%s/Arr::hasKey(\(.\+\),\s*\([^)]\+\))/array_key_exists(\2, \1).
 */
class Arr
{
    /**
     * Returns last item in array.
     */
    public static function last(array $array): mixed
    {
        $key = array_key_last($array);

        return is_null($key) ? $key : $array[$key];
    }

    /**
     * Returns first item in array.
     */
    public static function first(array $array): mixed
    {
        $key = array_key_first($array);

        return is_null($key) ? $key : $array[$key];
    }

    /**
     * Lazy implementation of range procedure.
     */
    public static function range(int $from, int $to): Generator
    {
        $step = $from < $to ? 1 : -1;

        while ($from !== $to + $step) {
            yield $from;
            $from += $step;
        }
    }
}
