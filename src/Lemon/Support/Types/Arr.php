<?php

namespace Lemon\Support\Types;

use Exception;

/**
 * TODO Str-like docblock
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
