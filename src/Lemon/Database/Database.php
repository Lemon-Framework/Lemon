<?php

declare(strict_types=1);

namespace Lemon\Database;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Database\Drivers\Driver;

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

    public function getConnection(): Driver
    {
        if (!$this->connection) {
            $this->connect();
        }

        return $this->connection;
    }

    public function connect(): void
    {
        $driver = $this->drivers[$this->config->part('database')->get('driver')] ?? null;
        if (!$driver) {
            throw new ConfigException('Config value database.driver is either missing or not valid driver');
        }

        $this->connection = new $driver($this);
    }

    /**
     * Sends query to database.
     *
     * @phpstan-param literal-string $query
     */
    public function query(string $query, ...$params)
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($params);

        return $statement;
    }
}
