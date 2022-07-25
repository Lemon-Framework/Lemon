<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon DB Zest
 * Provides static layer over the Lemon Database.
 *
 *
 * @see \Lemon\Database\Database
 */
class DB extends Zest
{
    public static function unit(): string
    {
        return 'database';
    }
}
