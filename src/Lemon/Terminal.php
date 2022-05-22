<?php

declare(strict_types=1);

namespace Lemon;

use Exception;

/**
 * Lemon Terminal Zest
 * Provides static layer over the Lemon Terminal.
 *
 * @see \Lemon\Terminal\Terminal
 */
class Terminal extends Zest
{
    public static function unit(): string
    {
        return 'terminal';
    }

    public static function run(): void
    {
        throw new Exception('Call to undefined method Terminal::run()');
    }
}
