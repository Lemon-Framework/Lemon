<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

use ArrayAccess;
use Exception;
use Iterator;

class Array_ implements Iterator, ArrayAccess
{
    /**
     * Array content.
     */
    public array $content;

    /** Iterating position */
    private int $position = 0;

    public function __construct(array $content = [])
    {
        $this->content = $content;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($key, $value): void
    {
        $this->set($key, $value);
    }

    public function current(): mixed
    {
        return $this->content[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->content[$this->position]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->content[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (is_string($offset)) {
            if (preg_match('/^(\\d+)\\.\\.(\\d+)?$/', $offset, $matches) === 1) {
                $from = (int) $matches[1];
                $len = isset($matches[2]) ? (int) $matches[2] - $matches[1] + 1 : null;

                return $this->slice($from, $len);
            }
        }
        if (is_int($offset)) {
            if ($offset < 0) {
                $offset = $this->lenght() + $offset;
            }
        }
        if (isset($this->content[$offset])) {
            return $this->content[$offset];
        }

        throw new Exception("Undefined array key {$offset}");
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->content[$offset]);
    }

    /**
     * Returns value for given key.
     *
     * @param mixed
     */
    public function get(mixed $key): mixed
    {
        if (! isset($this->content[$key])) {
            throw new Exception("Undefined array key {$key}");
        }

        return $this->content[$key];
    }

    /**
     * Sets value for given key.
     */
    public function set(mixed $key, mixed $value): self
    {
        $this->content[$key] = $value;

        return $this;
    }

    /**
     * Pushes value to the top of array.
     */
    public function push(mixed ...$values): self
    {
        array_push($this->content, ...$values);

        return $this;
    }

    /**
     * Pops item from the top of array.
     */
    public function pop(): mixed
    {
        return array_pop($this->content);
    }

    /**
     * Returns size of array.
     */
    public function lenght(): int
    {
        return sizeof($this->content);
    }

    /**
     * Returns size of array.
     */
    public function size(): int
    {
        return $this->lenght();
    }

    /**
     * Converts array to json.
     */
    public function json(): string
    {
        return json_encode($this->content);
    }

    /**
     * Exports nested Array_ as regular array.
     */
    public function export(): array
    {
        $parsed_arrays = [];
        foreach ($this->content as $key => $value) {
            $parsed_arrays[$key] = $value instanceof self ? $value->export() : $value;
        }

        return $parsed_arrays;
    }

    /**
     * Splits array into chunks.
     */
    public function chunk(int $lenght): self
    {
        $array = new self([new self()]);
        $counter = 0;
        foreach ($this as $item) {
            ++$counter;
            $array[-1][] = $item;
            if ($counter === $lenght) {
                $array[] = new self();
                $counter = 0;
            }
        }

        return $array;
    }

    /**
     * Determins whenever array contains given key.
     */
    public function hasKey(mixed $key): bool
    {
        return array_key_exists($key, $this->content);
    }

    /**
     * Filters array by given callback.
     */
    public function filter(callable $callback): self
    {
        return new self(array_filter($this->content, $callback));
    }

    /**
     * Returns first key of array.
     */
    public function firstKey(): mixed
    {
        return array_key_first($this->content);
    }

    /**
     * Returns last key of array.
     */
    public function lastKey(): mixed
    {
        return array_key_last($this->content);
    }

    /**
     * Returns first item from array.
     */
    public function first(): mixed
    {
        $key = $this->firstKey();

        return is_null($key) ? $key : $this->content[$key];
    }

    /**
     * Returns last item from array.
     */
    public function last(): mixed
    {
        $key = $this->lastKey();

        return is_null($key) ? $key : $this->content[$key];
    }

    /**
     * Returns all array keys.
     */
    public function keys(): self
    {
        return new self(array_keys($this->content));
    }

    /**
     * Returns all array items.
     */
    public function values(): self
    {
        return new self(array_values($this->content));
    }

    public function items(): self
    {
        return $this->values();
    }

    /**
     * Applies given callback to each item of array.
     */
    public function map(callable $callback): self
    {
        return new self(array_map($callback, $this->content));
    }

    /**
     * Merges all given arrays into curent.
     */
    public function merge(array|Array_ ...$arrays): self
    {
        $arrays = (new Array_($arrays))->export();

        return new self(array_merge($this->content, ...$arrays));
    }

    /**
     * Returns random key from array.
     *
     * @param int $count=1
     */
    public function randomKey(int $count = 1): mixed
    {
        return $this->content ? array_rand($this->content, $count) : null;
    }

    /**
     * Returns random item from array.
     *
     * @param int $count = 1
     */
    public function random(int $count = 1): mixed
    {
        $key = $this->randomKey($count);

        return is_null($key) ? $key : $this->content[$key];
    }

    /**
     * Randomly shuffles array.
     */
    public function shuffle(): self
    {
        $content = $this->content;
        shuffle($content);

        return new self($content);
    }

    /**
     * Replaces values in array with values from passed arrays.
     */
    public function replace(array|Array_ ...$replacements): self
    {
        $replacements = (new self($replacements))->export();

        return new self(array_replace($this->content, ...$replacements));
    }

    /**
     * Puts item in array in reverse order.
     */
    public function reverse(): self
    {
        return new self(array_reverse($this->content));
    }

    /**
     * Extract a slice of the array.
     *
     * @param mixed int $lenght
     */
    public function slice(int $start, ?int $lenght = null): self
    {
        return new self(array_slice($this->content, $start, $lenght));
    }

    /**
     * Returns sum of array.
     */
    public function sum(): int|float
    {
        return array_sum($this->content);
    }

    /**
     * Determins whenever array contains given needle.
     */
    public function contains(mixed $needle): bool
    {
        return in_array($needle, $this->content);
    }

    /**
     * Determins whenever array contains given needle.
     */
    public function has(mixed $needle): bool
    {
        return $this->contains($needle);
    }
}
