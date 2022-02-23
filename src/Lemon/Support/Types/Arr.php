<?php

namespace Lemon\Support\Types;

use Exception;

/**
 * Static layer for array manipulation 
 *
 * @method static \Lemon\Support\Types\Array_ push(...$values) Pushes value to the top of array
 * @method static \Lemon\Support\Types\Array_ pop() Pops item from the top of array
 * @method static int lenght() Returns size of array
 * @method static int size() Returns size of array
 * @method static string json() Converts array to json
 * @method static array export() Exports nested Array_ as regular array
 * @method static \Lemon\Support\Types\Array_ chunk(int $lenght) Splits array into chunks
 * @method static bool hasKey($key) Determins whenever array contains given key
 * @method static \Lemon\Support\Types\Array_ filter(callable $callback) Filters array by given callback
 * @method static mixed firstKey() Returns first key of array
 * @method static mixed lastKey() Returns last key of array
 * @method static mixed first() Returns first item from array
 * @method static mixed last() Returns last item from array
 * @method static \Lemon\Support\Types\Array_ keys() Returns all array keys
 * @method static \Lemon\Support\Types\Array_ values() Returns all array items
 * @method static \Lemon\Support\Types\Array_ map(callable $callback) Applies given callback to each item of array
 * @method static \Lemon\Support\Types\Array_ merge(array|Array_ ...$arrays) Merges all given arrays into curent
 * @method static mixed random(int $count=1) Returns random item from array
 * @method static \Lemon\Support\Types\Array_ shuffle() Randomly shuffles array
 * @method static \Lemon\Support\Types\Array_ replace(array|Array_ ...$replacements) Replaces elements from passed arrays into array
 * @method static \Lemon\Support\Types\Array_ reverse() Puts item in array in reverse order
 * @method static int|float sum() Extract a slice of the array
 * @method static bool contains($needle) Determins whenever array contains given needle
 * @method static bool has($needle) Determins whenever array contains given needle
 * @method static \Lemon\Support\Types\Array_ push(...$values) Pushes value to the top of array
 * @method static \Lemon\Support\Types\Array_ pop() Pops item from the top of array
 * @method static int lenght() Returns size of array
 * @method static int size() Returns size of array
 * @method static string json() Converts array to json
 * @method static array export() Exports nested Array_ as regular array
 * @method static \Lemon\Support\Types\Array_ chunk(int $lenght) Splits array into chunks
 * @method static bool hasKey($key) Determins whenever array contains given key
 * @method static \Lemon\Support\Types\Array_ filter(callable $callback) Filters array by given callback
 * @method static mixed firstKey() Returns first key of array
 * @method static mixed lastKey() Returns last key of array
 * @method static mixed first() Returns first item from array
 * @method static mixed last() Returns last item from array
 * @method static \Lemon\Support\Types\Array_ keys() Returns all array keys
 * @method static \Lemon\Support\Types\Array_ values() Returns all array items
 * @method static \Lemon\Support\Types\Array_ map(callable $callback) Applies given callback to each item of array
 * @method static \Lemon\Support\Types\Array_ merge(array|Array_ ...$arrays) Merges all given arrays into curent
 * @method static mixed random(int $count=1) Returns random item from array
 * @method static \Lemon\Support\Types\Array_ shuffle() Randomly shuffles array
 * @method static \Lemon\Support\Types\Array_ replace(array|Array_ ...$replacements) Replaces elements from passed arrays into array
 * @method static \Lemon\Support\Types\Array_ reverse() Puts item in array in reverse order
 * @method static int|float sum() Extract a slice of the array
 * @method static bool contains($needle) Determins whenever array contains given needle
 * @method static bool has($needle) Determins whenever array contains given needle
 *
 * @see \Lemon\Support\Types\Array_
 */
class Arr
{
    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, get_class_methods(Array_::class)) && $name != "from") {
            return (new Array_($arguments[0]))->$name(...array_slice($arguments, 1));
        }

        throw new Exception("Call to undefined method Arr::{$name}()");
    }

    public static function fromJson(String|String_ $subject)
    {
        return json_decode($subject, true);
    }

    public static function range(int $from, int $to, int $increment=1)
    {
        return new Array_(range($from, $to, $increment));
    }
}
