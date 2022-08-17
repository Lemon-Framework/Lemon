<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

use Exception;

class Nothing extends Maybe
{
    public function unwrap(mixed $default = null): mixed
    {
        return $default;
    }

    public function expect(string $error): mixed
    {
        throw new Exception($error);
    }

    public function then(callable $action): static
    {
        return $this;
    }
}
