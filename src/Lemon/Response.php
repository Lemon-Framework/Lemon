<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Response Zest
 * Provides static layer over the Lemon Response Factory.
 *
 * @see \Lemon\Http\ResponseFactory
 */
class Response extends Zest
{
    public static function unit(): string
    {
        return 'response';
    }
}
