<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

abstract class Maybe
{
    public static function just(mixed $data): Just
    {
        return new Just($data);
    }

    public static function nothing(): Nothing
    {
        return new Nothing();
    }

    abstract public function unwrap(mixed $default = null): mixed;

    abstract public function then(callable $action): static;

    abstract public function expect(string $error): mixed;
}
