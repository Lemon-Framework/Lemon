<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

class Postre extends Driver
{
    protected function getConnection(): array
    {
        $result = 'pgsql:';
        foreach (['host', 'port', 'dbname', 'user', 'password', 'sslmode'] as $key) {
            $value = $this->config->get('database'.$key);

            $result .= $key.'='.$value.';';
        }

        return [$result];
    }
}
