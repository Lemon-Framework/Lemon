<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Session Zest
 * Provides static layer over the Lemon Session.
 *
 * @method static void                init()                                 Starts session if not started.
 * @method static \Lemon\Http\Session expireAt(int $seconds)                 Sets expiration.
 * @method static \Lemon\Http\Session dontExpire()                           Removes expiration.
 * @method static string              get(string $key)                       Returns value of given key.
 * @method static \Lemon\Http\Session set(string $key, mixed $value)         Sets value for given key.
 * @method static bool                has(string $key)                       Determins whenever key exists.
 * @method        static              \Lemon\Http\Sessionremove(string $key) Removes key.
 * @method static void                clear()                                Clears session.
 *
 * @see \Lemon\Http\Session
 */
class Session extends Zest
{
    public static function unit(): string
    {
        return 'session';
    }
}
