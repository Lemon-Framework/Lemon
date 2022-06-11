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
        return new self($value);
    }

    public function with(mixed $value): static
    {
        $this->args[] = $value;

        return $this;
    }

    /**
     * Calls function with curent value.
     *
     * @see https://www.youtube.com/watch?v=oqwzuiSy9y0
     */
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
