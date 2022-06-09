<?php

declare(strict_types=1);

namespace Lemon\Database;

use PDO;

class Connection
{
    private PDO $connection;

    public function connect()
    {
    }

    /**
     * Sends query to database.
     *
     * @phpstan-param literal-string $query
     */
    public function query(string $query)
    {
    }
}
