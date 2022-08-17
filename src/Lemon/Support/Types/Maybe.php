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

    abstract function unwrap(mixed $default = null): mixed;

    abstract function then(callable $action): static;

    abstract function expect(string $error): mixed;
}
