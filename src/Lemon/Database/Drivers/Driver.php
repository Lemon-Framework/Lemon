<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

use Lemon\Config\Part;
use PDO;

abstract class Driver extends PDO
{
    public function __construct(
        public readonly Part $config
    ) {
        parent::__construct(...$this->getConnection());
    }


    abstract protected function getConnection(): array;
}
