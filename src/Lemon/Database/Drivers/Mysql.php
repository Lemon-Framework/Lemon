<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

use Lemon\Config\Exceptions\ConfigException;

class Mysql extends Driver
{
    protected function getConnection(): array
    {
        $result = [];
        foreach (['host', 'port', 'dbname', 'unix_socket', 'charset', 'user', 'password'] as $key) {
            $value = $this->config->get($key);

            if (is_null($value)) {
                throw new ConfigException('Config value for '.$key.' is missing while connecting to Postgre');
            }

            $result[] = [$key, $value];
        }

        return [
            implode(';', 
                array_map(
                    fn($item) => $item[0].'='.$item[1],
                    array_slice($result, -2)
                )
            ),
            $result[5],
            $result[6]
        ];
    }
}
