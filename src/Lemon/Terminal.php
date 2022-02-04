<?php

namespace Lemon;

use Lemon\Zest;

/**
 * Lemon Router Zest
 * Provides static layer over the Lemon Router
 *
 * @see \Lemon\Terminal\Terminal
 */
class Terminal extends Zest
{
    public static function unit()
    {
        return 'terminal';
    }
}
