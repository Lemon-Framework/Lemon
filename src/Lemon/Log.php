<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Logger Zest
 * Provides static layer over the Lemon log.
 *
 * @see \Lemon\Logging\Logger
 */
class Log extends Zest
{
    public static function unit(): string
    {
        return 'log';
    }
}
