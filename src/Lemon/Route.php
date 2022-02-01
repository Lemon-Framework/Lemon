<?php

namespace Lemon;

use Lemon\Zest;

/**
 * Lemon Router Zest
 * Provides static layer over the Lemon Router
 *
 * @see \Lemon\Http\Routing\Router
 */
class Route extends Zest
{
    public static function unit()
    {
        return 'routing';
    }
}
