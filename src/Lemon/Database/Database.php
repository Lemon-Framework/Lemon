<?php

declare(strict_types=1);

namespace Lemon\Database;

use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Database\Database as DatabaseContract;
use Lemon\Database\Drivers\Driver;
use PDOStatement;

class Database implements DatabaseContract
{
    private ?Driver $connection = null;

    private array $drivers = [
        'sqlite' => \Lemon\Database\Drivers\Sqlite::class,
        'postgre' => \Lemon\Database\Drivers\Postre::class,
        'mysql' => \Lemon\Database\Drivers\Mysql::class,
    ];

    public readonly string $driver;

    public function __construct(
        public readonly Config $config
    ) {
        $this->driver = $this->config->get('database.driver');
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
     * Returns driver name
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Creates new Driver and connects to database.
     */
    private function connect(): void
    {
        $driver = $this->drivers[$this->driver];

        $this->connection = new $driver($this->config);
    }
}
