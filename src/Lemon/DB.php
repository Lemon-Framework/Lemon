<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon DB Zest
 * Provides static layer over the Lemon Database.
 *
 * @method static Driver getConnection() Returns current driver and creates new if isn't already.
 * @method static void connect()         Creates new Driver and connects to database.
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
