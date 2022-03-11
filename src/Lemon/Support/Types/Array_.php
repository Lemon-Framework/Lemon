<?php

namespace Lemon\Support\Types;

use ArrayAccess;
use Exception;
use Iterator;

class Array_ implements Iterator, ArrayAccess
{
    /** 
     * Array content 
     * 
     * @var array
     */
    public array $content;

    /** Iterating position */
    private int $position = 0;

    public function __construct(array $content=[])
    {
        $this->content = $content;
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
        $this->position++;
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
        if (preg_match("/^(\d+)\.\.(\d+)?$/", $offset, $matches) === 1) {
            $from = (int) $matches[1];
            $len = isset($matches[2]) ? (int) $matches[2] - $matches[1] + 1 : null;
            return $this->slice($from, $len);
        }
        if (is_int($offset)) {
            if ($offset < 0) {
                $offset = $this->lenght() + $offset;
            }
        }
        if (isset($this->content[$offset])) {
            return $this->content[$offset];
        } else {
            throw new Exception("Undefined array key $offset");
        }
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
     * Returns value for given key
     *
     * @param mixed $key
     * @param mixed
     */
    public function get($key): mixed
    {
        if (!isset($this->content[$key])) {
    throw new Exception("Undefined array key $key");
        }
        return $this->content[$key];
    }

    /**
     * Sets value for given key
     *
     * @param mixed $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value): self
    {
        $this->content[$key] = $value;
        return $this;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Pushes value to the top of array
    *
     * @param mixed ...$values 
     * @return self
     */
    public function push(...$values): self
    {
    array_push($this->content, ...$values);
        return $this;
    }

    /**
     * Pops item from the top of array
     *
     * @return mixed
     */
    public function pop(): mixed
    {
        $value = array_pop($this->content);
        return $value;
    }

    /**
     * Returns size of array
     *
     * @return int
     */
    public function lenght(): int
    {
        return sizeof($this->content);
    }

    /**
     * Returns size of array
     *
     * @return int
     */
    public function size(): int
    {
    return $this->lenght();
    }

    /**
     * Converts array to json
    *
     * @return string
     */
    public function json(): string
    {
        return json_encode($this->content);
    }

    /**
     * Exports nested Array_ as regular array
     *
     * @return array
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
     * Splits array into chunks
     *
     * @param int $lenght
     * @return self
     */
    public function chunk(int $lenght): self 
    {
        $array = new self([new self()]);
        $counter = 0;
        foreach ($this as $item) {
            $counter++;
            $array[-1][] = $item;
            if ($counter == $lenght) {
                $array[] = new self();
                $counter = 0;
            }
        }
        return $array;
    }

    /**
     * Determins whenever array contains given key
     *
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key): bool
    {
        return array_key_exists($key, $this->content);
    }

    /**
     * Filters array by given callback
     *
     * @param callable $callback
     * @return self
     */
    public function filter(callable $callback): self
    {
        return new self(array_filter($this->content, $callback));
    }

    /**
     * Returns first key of array
     *
     * @return mixed
     */
    public function firstKey(): mixed
    {
        return array_key_first($this->content);
    }

    /**
     * Returns last key of array
     *
     * @return mixed
     */
    public function lastKey(): mixed
    {
        return array_key_last($this->content);
    }

    /**
     * Returns first item from array
     *
     * @return mixed
     */
    public function first(): mixed
    {
        return $this->content[$this->firstKey()];
    }

    /**
     * Returns last item from array
     *
     * @return mixed
     */
    public function last(): mixed
    {
        return $this->content[$this->lastKey()];
    }

    /**
     * Returns all array keys
     *
     * @return self
     */
    public function keys(): self
    {
        return new self(array_keys($this->content));
    }

    /**
     * Returns all array items
     *
     * @return self
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
     * Applies given callback to each item of array
     *
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback): self
    {
        return new self(array_map($callback, $this->content));
    }

    /**
     * Merges all given arrays into curent
     *
     * @param array|Array_ ...$arrays
     * @return self
     */
    public function merge(array|Array_ ...$arrays): self
    {
        $arrays = (new Array_($arrays))->export();
        return new self(array_merge($this->content, ...$arrays));
    }

    /**
     * Returns random key from array
     *
     * @param int $count=1
     * @return mixed
     */
    public function randomKey(int $count = 1): mixed
    {
        return array_rand($this->content, $count);
    }

    /**
     * Returns random item from array
     *
     * @param int $count = 1
     * @return mixed
     */
    public function random(int $count = 1): mixed
    {
        return $this->content[$this->randomKey($count)];
    }

    /**
     * Randomly shuffles array
     *
     * @return self
     */
    public function shuffle(): self
    {
        $content = $this->content;
        shuffle($content);
        return new self($content);
    }

    /**
     * Replaces values in array with values from passed arrays
     *
     * @param array|Array_ ...$replacements
     * @return self
     */
    public function replace(array|Array_ ...$replacements): self
    {
        $replacements = (new self($replacements))->export();
        return new self(array_replace($this->content, ...$replacements));
    }

    /**
     * Puts item in array in reverse order
     *
     * @return self
     */
    public function reverse(): self
    {
        return new self(array_reverse($this->content));
    }

    /**
     * Extract a slice of the array
     *
     * @param int $start
     * @param mixed int $lenght
     * @return self
     */
    public function slice(int $start, int $lenght = null): self
    {
        return new self(array_slice($this->content, $start, $lenght));
    }

    /**
     * Returns sum of array
     *
     * @return int|float
     */
    public function sum(): int|float
    {
        return array_sum($this->content);
    }

    /**
     * Determins whenever array contains given needle
     *
     * @param mixed $needle
     * @return bool
     */
    public function contains($needle): bool
    {
        return in_array($needle, $this->content);
    }

    /**
     * Determins whenever array contains given needle
     *
     * @param mixed $needle
     * @return bool
     */
    public function has($needle): bool
    {
        return $this->contains($needle);
    }
}

