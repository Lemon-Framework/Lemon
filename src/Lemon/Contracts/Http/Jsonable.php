r?php

declare(strict_types=1);

namespace Lemon\Http;

interface Jsonable
{
    /**
     * Returns json from class data.
     */
    public function toJson(): array;
}
