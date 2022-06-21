<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

use Lemon\Config\Exceptions\ConfigException;

class Sqlite extends Driver
{
    protected function getConnection(): array
    {
        $file = $this->config->get('file');
        if (!$file) {
            throw new ConfigException('Config value database.file is missing while using sqlite driver');
        }

        return ['sqlite:'.$file];
    }
}
