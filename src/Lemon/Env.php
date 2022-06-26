<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Env Zest
 * Provides static layer over the Lemon Env.
 *
 * @method static void load()                                   Loads env file
 * @method static mixed get(string $key, mixed $default = null) Returns env value of given key or default if not present
 * @method static bool has(string $key)                         Returns whenever env key exist
 * @method static void set(string $key, string $value)          Sets env key with given value
 * @method static array data()                                  Returns env data
 * @method static void commit()                                 Saves data back to env file
 *
 * @see \Lemon\Support\Env
 */
class Env extends Zest
{
    public static function unit(): string
    {
        return 'env';
    }
}
