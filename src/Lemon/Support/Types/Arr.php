<?php

namespace Lemon\Support\Types;

use Exception;

/**
 * Static layer for array manipulation 
 *
 * @method static \Lemon\Support\Types\Array_ push(array $array, ...$values) Pushes value to the top of array
 * @method static \Lemon\Support\Types\Array_ pop(array $array) Pops item from the top of array
 * @method static int lenght(array $array) Returns size of array
 * @method static int size(array $array) Returns size of array
 * @method static string json(array $array) Converts array to json
 * @method static array export(array $array) Exports nested Array_ as regular array
 * @method static \Lemon\Support\Types\Array_ chunk(array $array, int $lenght) Splits array into chunks
 * @method static bool hasKey(array $array, $key) Determins whenever array contains given key
 * @method static \Lemon\Support\Types\Array_ filter(array $array, callable $callback) Filters array by given callback
 * @method static mixed firstKey(array $array) Returns first key of array
 * @method static mixed lastKey(array $array) Returns last key of array
 * @method static mixed first(array $array) Returns first item from array
 * @method static mixed last(array $array) Returns last item from array
 * @method static \Lemon\Support\Types\Array_ keys(array $array) Returns all array keys
 * @method static \Lemon\Support\Types\Array_ values(array $array) Returns all array items
 * @method static \Lemon\Support\Types\Array_ map(array $array, callable $callback) Applies given callback to each item of array
 * @method static \Lemon\Support\Types\Array_ merge(array $array, array|Array_ ...$arrays) Merges all given arrays into curent
 * @method static mixed random(array $array, int $count=1) Returns random item from array
 * @method static \Lemon\Support\Types\Array_ shuffle(array $array) Randomly shuffles array
 * @method static \Lemon\Support\Types\Array_ replace(array $array, array|Array_ ...$replacements) Replaces elements from passed arrays into array
 * @method static \Lemon\Support\Types\Array_ reverse(array $array) Puts item in array in reverse order
 * @method static int|float sum(array $array) Extracts a slice of the array
 * @method static bool contains(array $haystack, $needle) Determins whenever array contains given needle
 * @method static bool has(array $haystack, $needle) Determins whenever array contains given needle
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

    public static function fromJson(string $subject)
    {
        return json_decode($subject, true);
    }

    public static function from(array $array)
    {
        return new Array_($array);
    }

    public static function of(...$data)
    {
        return new Array_($data);
    }

    public static function range(int $from, int $to, int $increment=1)
    {
        return new Array_(range($from, $to, $increment));
    }
}
