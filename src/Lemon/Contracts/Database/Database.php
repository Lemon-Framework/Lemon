<?php

declare(strict_types=1);

namespace Lemon\Contracts\Database;

use Lemon\Database\Drivers\Driver;
use PDOStatement;

interface Database
{
    /**
     * Returns current driver and creates new if isn't already.
     */
    public function getConnection(): Driver;

    /**
     * Sends query to database.
     *
     * @phpstan-param literal-string $query
     */
    public function query(string $query, ...$params): PDOStatement;
}
