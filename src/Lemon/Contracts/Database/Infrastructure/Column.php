<?php

declare(strict_types=1);

namespace Lemon\Contracts\Database\Infrastructure;

interface Column
{
    public function build(string $database): string;
}
