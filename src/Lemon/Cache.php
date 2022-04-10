<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Static layer over Lemon Cache.
 *
 * @see \Lemon\Cache\Cache
 */
class Cache extends Zest
{
    public static function unit(): string
    {
        return 'cache';
    }
}
