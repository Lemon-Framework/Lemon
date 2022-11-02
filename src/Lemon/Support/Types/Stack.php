<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

class Stack
{
    private array $storage = [];

    public function __construct(
        private string $type = 'mixed'
    ) {
    }

    /**
     * Creates new Stack with given type.
     */
    public static function withType(string $type): self
    {
        return new self($type);
    }

    /**
     * Pushes item to the stack.
     */
    public function push(mixed $value): static
    {
        if (!Type::is($this->type, $value)) {
            throw new \InvalidArgumentException('Argument 1 of function '.static::class.'::push() must be type '.$this->type.' '.gettype($value).' given');
        }
        $this->storage[] = $value;

        return $this;
    }

    /**
     * Pops item from the stack.
     */
    public function pop(): mixed
    {
        return array_pop($this->storage);
    }

    /**
     * Returns top of the stack.
     */
    public function top(): mixed
    {
        return $this->storage[count($this->storage) - 1];
    }

    /**
     * Returns size of the stack.
     */
    public function size(): int
    {
        return count($this->storage);
    }

    /**
     * Returns stack as array.
     */
    public function storage(): array
    {
        return $this->storage;
    }
}
