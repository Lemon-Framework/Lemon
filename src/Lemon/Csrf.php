<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Csrf Zest
 * Provides static layer over the Lemon Csrf.
 *
 * @method static string getToken()              Returns csrf token and creates new if does not exist
 * @method static void   reset()                 Removes token from cookies
 * @method static bool   validate(string $token) Returns whenever given token equals token in cookies
 *
 * @see \Lemon\Protection\Csrf
 */
class Csrf extends Zest
{
    public static function unit(): string
    {
        return 'csrf';
    }
}
