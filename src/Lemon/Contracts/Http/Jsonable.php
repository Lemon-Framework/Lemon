<?php

declare(strict_types=1);

namespace Lemon\Contracts\Http;

interface Jsonable
{
    /**
     * Returns json from class data.
     */
    public function toJson(): array;
}
