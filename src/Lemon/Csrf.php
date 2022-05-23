<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Csrf Zest
 * Provides static layer over the Lemon Csrf.
 *
 * @see 
 */
class Csrf extends Zest
{
    public static function unit(): string
    {
        return 'csrf';
    }
}
