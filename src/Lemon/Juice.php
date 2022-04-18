<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Juice Zest
 * Provides static layer over the Lemon Juice engine.
 *
 * @see \Lemon\Templating\Juice\Compiler
 */
class Juice extends Zest
{
    public static function unit(): string
    {
        return 'juice';
    }
}
