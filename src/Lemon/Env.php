<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Env Zest
 * Provides static layer over the Lemon Env.
 *
 * @see \Lemon\Support\Env
 */
class Env extends Zest
{
    public static function unit(): string
    {
        return 'env';
    }
}
