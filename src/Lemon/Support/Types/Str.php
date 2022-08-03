<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

use Exception;

/**
 * @method static int size(string $subject)                                                              Lemon string type
 * @method static int len(string $subject)                                                               Returns size of string
 * @method static \Lemon\Support\Types\Array_ split(string $subject, string $separator)                  Splits string to array by separator
 * @method static \Lemon\Support\Types\String_ join(string $subject, array $array)                       Joins given Array items with string
 * @method static \Lemon\Support\Types\String_ capitalize(string $subject)                               Converts first character to uppercase
 * @method static \Lemon\Support\Types\String_ decapitalize(string $subject)                             Converts first character to lovercase
 * @method static \Lemon\Support\Types\String_ toLower(string $subject)                                  Converts string to lovercase
 * @method static \Lemon\Support\Types\String_ toUpper(string $subject)                                  Converts string to uppercase
 * @method static bool contains(string $subject, string $substring)                                      Returns whenever string contains given substring
 * @method static bool startsWith(string $subject, string $substring)                                    Returns whenever string starts with given substring
 * @method static bool endsWith(string $subject, string $substring)                                      Returns whenever string ends with given substring
 * @method static \Lemon\Support\Types\String_ replace(string $subject, string $search, string $replace) Replaces all occurences of given search string with replace string
 * @method static \Lemon\Support\Types\String_ shuffle(string $subject)                                  Randomly shuffles string
 * @method static \Lemon\Support\Types\String_ reverse(string $subject)                                  Reverses string
 *
 * @see \Lemon\Support\Types\String_
 */
class Str
{
    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, get_class_methods(String_::class)) && 'from' !== $name) {
            return String_::from($arguments[0])->{$name}(...array_slice($arguments, 1));
        }

        throw new Exception("Call to undefined method Str::{$name}()");
    }

    /**
     * Creates new String_ instance.
     */
    public static function from(string $subject): String_
    {
        return new String_($subject);
    }

    /**
     * Returns random string.
     */
    public static function random(int $size): string
    {
        $chars = str_split(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));

        return array_reduce(array_rand($chars, $size), fn ($carry, $item) => $carry.$chars[$item]);
    }
}
