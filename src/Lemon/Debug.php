<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Debug Zest
 * Provides static layer over the Lemon Debugging.
 *
 * @see
 */
class Debug extends Zest
{
    public static function unit(): string
    {
        return 'dumper';
    }
}
