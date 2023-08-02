<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

/**
 * Class providing simple actions with arrays
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
    public static function range(int $from, int $to): \Generator
    {
        $step = $from < $to ? 1 : -1;

        while ($from !== $to + $step) {
            yield $from;
            $from += $step;
        }
    }

    /**
     * Applies callback to each item in array.
     */
    public static function map(callable $callback, iterable $array): iterable
    {
        foreach ($array as $key => $value) {
            $array[$key] = $callback($value, $key);
        }

        return $array;
    }
}
