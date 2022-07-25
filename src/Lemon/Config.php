<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Static layer over Lemon Config.
 *
 * @method static static load(string $directory = 'config')                                                    Loads config data from given directory.
 * @method static mixed get(string $key)                                                                       Returns value for given key in config
 * @method static string file(string $key, string $extension = null)                                           Returns project file for given key in config.
 * @method static static set(string $key, mixed $value)                                                        Sets key in config for given value.
 * @method static void loadPart(string $part, bool $force = false) Loads part (if not loaded or force is true) into static::$data.
 * @method static array data()                                                                                 Returns all config data
 *
 * @see \Lemon\Config\Config
 */
class Config extends Zest
{
    public static function unit(): string
    {
        return 'config';
    }
}
