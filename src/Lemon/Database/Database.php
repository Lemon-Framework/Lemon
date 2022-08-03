<?php

declare(strict_types=1);

namespace Lemon\Database;

use Lemon\Config\Config;
use Lemon\Database\Drivers\Driver;
use PDOStatement;

class Database
{
    private ?Driver $connection = null;

    private array $drivers = [
        'sqlite' => \Lemon\Database\Drivers\Sqlite::class,
        'postgre' => \Lemon\Database\Drivers\Postre::class,
        'mysql' => \Lemon\Database\Drivers\Mysql::class,
    ];

    public function __construct(
        public readonly Config $config
    ) {
    }

    /**
     * Returns current driver and creates new if isn't already.
     */
    public function getConnection(): Driver
    {
        if (!$this->connection) {
            $this->connect();
        }

        return $this->connection;
    }

    /**
     * Sends query to database.
     *
     * @phpstan-param literal-string $query
     */
    public function query(string $query, ...$params): PDOStatement
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($params);

        return $statement;
    }

    /**
     * Creates new Driver and connects to database.
     */
    private function connect(): void
    {
        $driver = $this->drivers[$this->config->get('database.driver')];

        $this->connection = new $driver($this->config);
    }
}
