<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Highlighter Zest
 * Provides static layer over the Lemon Highlighter.
 *
 * @method static string highlight(string $code)
 * @see \Lemon\Highlighter\Highlighter
 */
class Highlighter extends Zest
{
    public static function unit(): string
    {
        return 'highlighter';
    }
}
