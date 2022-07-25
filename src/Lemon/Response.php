<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Response Zest
 * Provides static layer over the Lemon Response Factory.
 *
 * @method static Response error(int $code) Returns response of 400-500 http status codes.
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
