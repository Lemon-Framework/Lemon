<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

use Lemon\Config\Exceptions\ConfigException;

class Postre extends Driver
{
    protected function getConnection(): array
    {
        $result = 'pgsql:';
        foreach (['host', 'port', 'dbname', 'user', 'password', 'sslmode'] as $key) {
            $value = $this->config->get($key);

            if (is_null($value)) {
                throw new ConfigException('Config value for '.$key.' is missing while connecting to Postgre');
            }

            $result .= $key.'='.$value.';';
        }
        return [$result];
    }
}
