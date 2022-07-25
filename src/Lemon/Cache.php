<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Static layer over Lemon Cache.
 *
 * @method static void load() Cached data.
 * @method static array data() Returns cached data.
 * @method static mixed get(string $key, mixed $default = null) Returns cached value or default value.
 * @method static bool set(string $key, mixed $value, null|int|DateInterval $ttl = null) Sets new value to cache.
 * @method static bool delete(string $key) Removes value from cache.
 * @method static bool clear() Clears cache.
 * @method static iterable getMultiple(iterable $keys, mixed $default = null) Returns value for every given key.
 * @method static bool deleteMultiple(iterable $keys) Removes item for every given key.
 * @method static bool setMultiple(iterable $values, null|int|DateInterval $ttl = null) Sets multiple items.
 * @method static bool has(string $key) Returns whenever key exist.
 * @method static bool commit() Saves data to file.
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
