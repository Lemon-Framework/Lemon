<?php

declare(strict_types=1);

namespace Lemon\Database\Drivers;

class Sqlite extends Driver
{
    protected function getConnection(): array
    {
        $file = $this->config->get('database.file');

        return ['sqlite:'.$file];
    }
}
