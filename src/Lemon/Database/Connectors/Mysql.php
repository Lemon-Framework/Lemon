<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

class Mysql extends Driver
{
    /**
     * {@inheritdoc}
     */
    protected function getConnection(): array
    {
        $result = [];
        foreach (['host', 'port', 'dbname', 'unix_socket', 'charset', 'user', 'password'] as $key) {
            $value = $this->config->get('database.'.$key);

            $result[] = [$key, $value];
        }

        return [
            implode(
                ';',
                array_map(
                    fn ($item) => $item[0].'='.$item[1],
                    array_slice($result, -2)
                )
            ),
            $result[5],
            $result[6],
        ];
    }
}
