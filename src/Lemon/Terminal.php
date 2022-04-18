<?php

declare(strict_types=1);

namespace Lemon;

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
}
