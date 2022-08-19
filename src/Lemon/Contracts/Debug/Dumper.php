<?php

declare(strict_types=1);

namespace Lemon\Contracts\Debug;

interface Dumper
{
    /**
     * Dumps given data.
     */
    public function dump(mixed $data): void;
}
