<?php

declare(strict_types=1);

namespace Lemon\Support;

use Exception;

class Pipe
{
    private array $args = [];

    public function __construct(
        private mixed $value
    ) {
    }

    public function __call($name, $arguments): static
    {
        if ('>>>=' !== $name) { // haha
            throw new Exception('Call to undefined method '.static::class.'::'.$name.'()');
        }

        return $this->then(...$arguments);
    }

    /**
     * Creates new instance with given value.
     */
    public static function send(mixed $value): self
    {
        return new self($value);
    }

    /**
     * Sets argument which will be autimaticaly passed in every callback.
     */
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

    /**
     * Returns curent value.
     */
    public function return(): mixed
    {
        return $this->value;
    }
}
