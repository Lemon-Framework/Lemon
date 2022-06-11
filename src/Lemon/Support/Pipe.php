<?php

declare(strict_types=1);

namespace Lemon\Support;

class Pipe
{
    private array $args = [];

    public function __construct(
        private mixed $value
    ) {
        
    }

    public static function send(mixed $value): static
    {
        return new static($value);
    }

    public function with(mixed $value): static
    {
        $this->args[] = $value;
        return $this;
    }

    public function then(callable $action): static
    {
        $this->value = $action($this->value, ...$this->args);
        return $this;
    }

    public function return(): mixed
    {
        return $this->value;
    }
}
