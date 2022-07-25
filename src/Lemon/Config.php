<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Static layer over Lemon Config.
 *

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
