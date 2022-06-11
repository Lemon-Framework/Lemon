<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

use InvalidArgumentException;

class Stack
{
    private array $storage = [];

    public function __construct(
        private string $type = 'mixed'
    ) {
        
    }

    public static function withType(string $type): self
    {
        return new self($type);
    }

    /**
     * @param T $value
     */
    public function push(mixed $value): static
    {
        if (!Type::is($this->type, $value)) {
            throw new InvalidArgumentException('Argument 1 of function '.static::class.'::push() must be type '.$this->type.' '.gettype($value).' given');
        }
        $this->storage[] = $value;
        return $this;
    }

    public function pop(): mixed
    {
        return array_pop($this->storage);
    }

    public function top(): mixed
    {
        return $this->storage[count($this->storage) - 1];
    }

    public function size(): int
    {
        return count($this->storage);
    }

    public function storage(): array
    {
        return $this->storage;
    }
}
