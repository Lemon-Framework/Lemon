<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

use Lemon\Config\Config;
use PDO;

abstract class Driver extends PDO
{
    public function __construct(
        public readonly Config $config
    ) {
        parent::__construct(...$this->getConnection());
    }

    /**
     * Returns array of pdo construct arguments.
     */
    abstract protected function getConnection(): array;
}
