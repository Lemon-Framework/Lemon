<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

class Just extends Maybe
{
    public function __construct(
        private mixed $data
    ) {
    }

    public function unwrap(mixed $default = null): mixed
    {
        return $this->data;
    }

    public function expect(string $error): mixed
    {
        return $this->data;
    }

    public function then(callable $action): static
    {
        $this->data = $action($this->data);

        return $this;
    }
}
