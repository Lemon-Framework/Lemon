<?php

namespace Lemon\Support\Types;

use Exception;

/**
 * @method static \Lemon\Support\Types\Array_ split(String|String_ $subject, String|String_ $separator="", int $lenght=0)
 * @method static \Lemon\Support\Types\String_ join(String|String_ $subject, Array|Array_ $array)
 * @method static \Lemon\Support\Types\String_ capitalize(String|String_ $subject)
 * @method static \Lemon\Support\Types\String_ decapitalize(String|String_ $subject)
 * @method static \Lemon\Support\Types\String_ toLower(String|String_ $subject)
 * @method static \Lemon\Support\Types\String_ toUpper(String|String_ $subject)
 * @method static \Lemon\Support\Types\String_ replace(String|String_ $subject, String|String_ $search, String|String_ $replace)
 * @method static \Lemon\Support\Types\String_ shuffle(String|String_ $subject)
 * @method static \Lemon\Support\Types\String_ reverse(String|String_ $subject)
 * @method static bool contains(String|String_ $subject, String|String_ $substring)
 * @method static bool startsWith(String|String_ $subject, String|String_ $substring)
 * @method static bool endsWith(String|String_ $subject, String|String_ $substring)
 *
 * @see \Lemon\Support\Types\String_
 */
class Str
{      
    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, get_class_methods(String_::class)) && $name != "from")
            return String_::from($arguments[0])->$name(...array_slice($arguments, 1));

        throw new Exception("Call to undefined method Str::{$name}()");
    }
}

